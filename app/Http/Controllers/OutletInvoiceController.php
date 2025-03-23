<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerDuePayment;
use App\Models\Outlet;
use App\Models\OutletHasUser;
use App\Models\OutletInvoice;
use App\Models\OutletInvoiceDetails;
use App\Models\OutletPayment;
use App\Models\OutletStock;
use App\Models\PaymentMethod;
use App\Models\RedeemPointLog;
use App\Models\SalesReturn;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\PathaoApiService;
use App\Jobs\SendSMSJob;
use App\Models\Settings;
use App\Models\ShortLink;
use SteadFast\SteadFastCourierLaravelPackage\Facades\SteadfastCourier;

class OutletInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $pathaoApiService, $setting;

    public function __construct(PathaoApiService $pathaoApiService)
    {
        $this->setting = Settings::first() ?? new Settings();
        $this->pathaoApiService = $pathaoApiService;
        $this->middleware('permission:invoice.management|invoice.create|invoice.edit|invoice.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:invoice.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:invoice.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:invoice.delete', ['only' => ['destroy']]);
        $this->middleware('permission:pay-due.payment', ['only' => ['dueList', 'payDue', 'paymentDue']]);
    }

    public function index()


    {

        return view('admin.Pos.index_pos');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payment_methods = PaymentMethod::pluck('method_name', 'id');

        $outlet_id = Auth::user()->outlet_id ?? Outlet::orderBy('id', 'desc')->value('id');

        $outlet = Outlet::where('id', $outlet_id)->first();
        // $outlet_id = OutletHasUser::where('user_id', Auth::user()->id)->first();

        return view('admin.Pos.pos', compact('outlet_id', 'payment_methods', 'outlet'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dump($request->all());
        $input = $request->all();

        // Validate the incoming request data
        $this->validateRequest($request);

        // Get or create customer
        $customer = $this->getOrCreateCustomer($input);

        // Calculate discounts and delivery charges
        $discount = $this->calculateDiscount($request);
        [$grandTotal, $totalWithDelivery] = $this->calculateTotalWithDelivery($input);

        // Prepare and create the invoice
        $outletInvoice = $this->createInvoice($input, $customer, $discount, $grandTotal, $totalWithDelivery);

        // Handle Pathao API integration if needed
        $checkOutletCourierActive = Outlet::where('id', $input['outlet_id'])->first();
        if ($checkOutletCourierActive->is_active_courier_gateway == 1) {
            if ($this->setting->courier_gateway != null && $this->setting->courier_gateway == 'pathao') {
                $this->handlePathaoOrderCreation($input, $customer, $outletInvoice, $totalWithDelivery);
            } else if ($this->setting->courier_gateway != null && $this->setting->courier_gateway == 'steadfast') {
                $this->handleSteadfastOrderCreation($input, $customer, $outletInvoice, $totalWithDelivery);
            }
        }

        // Log redeem points if applicable
        $this->logRedeemPoints($request, $customer, $outletInvoice);

        // Process and save invoice details and update stock
        $this->processInvoiceDetails($input, $outletInvoice);

        return response()->json(['data' => $outletInvoice]);
    }

    // Validates the incoming request data
    private function validateRequest($request)
    {
        $request->validate([
            'outlet_id' => 'required',
            'product_id' => 'required',
            'product_name' => 'required',
            'size' => 'required',
            'quantity' => 'required',
            'box_mrp' => 'required',
            'grand_total' => 'required',
            'payment_method_id' => 'required',
        ]);
    }

    // Retrieves or creates a customer
    private function getOrCreateCustomer($input)
    {
        if (empty($input['mobile']) || $input['mobile'] == '0000000000') {
            return $this->getOrCreateDefaultCustomer($input['outlet_id']);
        }

        return $this->getOrCreateNamedCustomer($input);
    }

    // Handles default 'walking customer' case
    private function getOrCreateDefaultCustomer($outletId)
    {
        $customer = Customer::where('outlet_id', $outletId)->where('mobile', 'LIKE', '%00000%')->first();
        $customerDetails = [
            'name' => 'Walking Customer',
            'mobile' => '0000000000',
            'address' => '',
            'outlet_id' => $outletId,
            'points' => 0,
        ];

        return $customer ?? Customer::create($customerDetails);
    }

    // Handles named customer creation or updating
    private function getOrCreateNamedCustomer($input)
    {
        $customer = Customer::where('mobile', $input['mobile'])->first();
        $customerDetails = [
            'name' => ucfirst($input['name']),
            'mobile' => $input['mobile'],
            'address' => $input['address'],
            'birth_date' => Carbon::parse($input['birth_date'])->toDateString(),
            'outlet_id' => $input['outlet_id'],
            'due_balance' => $input['due_amount'],
            'points' => round(($input['sub_total'] / 100), 2),
        ];

        if (!$customer) {
            $customer = Customer::create($customerDetails);
            if ($this->setting->sms_gateway == 'twilio' && $this->setting->sms_gateway != null) {
                $this->sendWelcomeSMS($input['mobile'], ucfirst($input['name']), $input['outlet_id']);
            }
        } else {
            $customer->update($customerDetails);
        }

        return $customer;
    }

    // Calculates total discount
    private function calculateDiscount($request)
    {
        if ($request->discount > 0 && $request->flatdiscount > 0) {
            return $request->discount + $request->flatdiscount;
        }

        return max($request->discount, $request->flatdiscount);
    }

    // Calculates total with or without delivery charges
    private function calculateTotalWithDelivery($input)
    {
        $grandTotal = round($input['grand_total']);
        $totalWithDelivery = $grandTotal + round($input['sub_total']) - round($input['totaldis']);

        if (round($input['delivery']) > 0) {
            $grandTotal -= round($input['delivery']);
        }

        return [$grandTotal, $totalWithDelivery];
    }

    // Creates the invoice
    private function createInvoice($input, $customer, $discount, $grandTotal, $totalWithDelivery)
    {
        $invoiceData = [
            'outlet_id' => $input['outlet_id'],
            'customer_id' => $customer->id,
            'sale_date' => Carbon::now(),
            'sub_total' => $input['sub_total'] + round($discount),
            'vat' => round($input['vat']),
            'delivery_charge' => round($input['delivery']),
            'total_discount' => round($discount),
            'grand_total' => $grandTotal,
            'total_with_charge' => $totalWithDelivery,
            'given_amount' => round($input['paid_amount']),
            'payable_amount' => round($input['payable_amount']),
            'paid_amount' => $input['paid_amount'] > $input['payable_amount'] ? round($input['payable_amount']) : $input['paid_amount'],
            'due_amount' => round($input['due_amount']),
            'redeem_point' => $input['redeem_points'],
            'earn_point' => round(($input['sub_total'] / 100), 2),
            'payment_method_id' => $input['payment_method_id'],
            'added_by' => Auth::user()->id,
        ];

        return OutletInvoice::create($invoiceData);
    }

    // Handles Pathao order creation and SMS notifications
    private function handlePathaoOrderCreation($input, $customer, $outletInvoice, $totalWithDelivery)
    {
        $parsedData = $this->pathaoApiService->parseAddress($input['address']);
        $orderData = [
            "store_id" => 6173,
            "merchant_order_id" => $outletInvoice->id,
            "recipient_name" => $input['name'],
            "recipient_phone" => $input['mobile'],
            "recipient_address" => $input['address'],
            "recipient_city" => $parsedData['district_id'],
            "recipient_zone" => $parsedData['zone_id'],
            "delivery_type" => 48,
            "item_type" => 2,
            "item_quantity" => 1,
            "item_weight" => 0.5,
            "amount_to_collect" => (int)$totalWithDelivery,
        ];

        $response = $this->pathaoApiService->createOrder($orderData);
        $shortLinkUrl = $this->generateShortTrackingLink($response, $outletInvoice);

        if ($this->setting->sms_gateway == 'twilio' && $this->setting->sms_gateway != null) {
            $this->sendOrderTrackingSMS($input['mobile'], ucfirst($input['name']), $shortLinkUrl);
        }
    }

    private function handleSteadfastOrderCreation($input, $customer, $outletInvoice, $totalWithDelivery)
    {
    
        $orderData =

            [

                'invoice' => $outletInvoice->id,

                'recipient_name' => $input['name'],

                'recipient_phone' => $input['mobile'],

                'recipient_address' => $input['address'],

                'cod_amount' => (int)$totalWithDelivery,

                'note' => 'Order from Shop Bah BD',

            ];

        $response = SteadfastCourier::placeOrder($orderData);

    }

    // Generates a short tracking link for the Pathao order
    private function generateShortTrackingLink($response, $outletInvoice)
    {
        $shortLink = ShortLink::create([
            'original_url' => "https://merchant.pathao.com/tracking?consignment_id=" . $response['data']['consignment_id'] . "&phone=" . $outletInvoice->phone,
            'short_code' => $outletInvoice->id,
        ]);

        return url("/track/{$outletInvoice->id}");
    }

    // Logs redeem points if applicable
    private function logRedeemPoints($request, $customer, $outletInvoice)
    {
        if (isset($request->redeem_points) && ($request->redeem_points > 0)) {
            RedeemPointLog::create([
                'outlet_id' => $customer->outlet_id,
                'customer_id' => $customer->id,
                'invoice_id' => $outletInvoice->id,
                'previous_point' => $customer->points,
                'redeem_point' => $request->redeem_points,
            ]);
        }
    }

    // Processes each invoice detail item and updates stock
    private function processInvoiceDetails($input, $outletInvoice)
    {
        $medicines = $input['product_name'];
        for ($i = 0; $i < sizeof($medicines); $i++) {
            OutletInvoiceDetails::create([
                'outlet_invoice_id' => $outletInvoice->id,
                'stock_id' => $input['stock_id'][$i],
                'medicine_id' => $input['product_id'][$i],
                'medicine_name' => $input['product_name'][$i],
                'size' => $input['size'][$i],
                'available_qty' => $input['stockquantity'][$i] - $input['quantity'][$i],
                'quantity' => $input['quantity'][$i],
                'rate' => $input['box_mrp'][$i],
                'discount' => round($input['totaldis'][$i]),
                'total_price' => round($input['total'][$i]) + round($input['totaldis'][$i]),
                'remarks' => strtoupper($input['remarks'][$i]),
            ]);

            // Update stock quantity
            $this->updateStockQuantity($input, $i);
        }
    }

    // Updates stock quantity for a specific product
    private function updateStockQuantity($input, $index)
    {
        OutletStock::where('outlet_id', $input['outlet_id'])
            ->where('medicine_id', $input['product_id'][$index])
            ->where('size', '=', $input['size'][$index])
            ->decrement('quantity', $input['quantity'][$index]);
    }

    // Sends a welcome SMS
    private function sendWelcomeSMS($mobile, $customerName, $outletId)
    {
        if ($outletId != 4) {
            $outletName = Outlet::where('id', $outletId)->value('outlet_name');
            $text = "Thanks for shopping $outletName, We hope to see you again soon! See what's new: https://stolen.com.bd";
            SendSMSJob::dispatch('880' . substr($mobile, 1), $text);
        }
    }

    // Sends an order tracking SMS
    private function sendOrderTrackingSMS($mobile, $customerName, $trackingUrl)
    {
        $text = "Hey $customerName, thanks for shopping at Stolen! Your order is on its way! Tracking: $trackingUrl";
        SendSMSJob::dispatch('880' . substr($mobile, 1), $text);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\OutletInvoice $outletInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(OutletInvoice $outletInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\OutletInvoice $outletInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit(OutletInvoice $outletInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\OutletInvoice $outletInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutletInvoice $outletInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\OutletInvoice $outletInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutletInvoice $outletInvoice)
    {
        //
    }


    public function getoutletStock(Request $request)
    {
        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');

        $search = $request->search;
        if ($search == '') {

            $medicines = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $outlet_id)->where('outlet_stocks.quantity', '>', '0')
                ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
                ->select('outlet_stocks.id as id', 'medicines.category_id as category_id', 'outlet_stocks.size as size', 'medicines.medicine_name as medicine_name', 'medicines.id as medicine_id')->limit(20)->get();
        } else {

            $medicines = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $outlet_id)->where('outlet_stocks.quantity', '>', '0')
                ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
                ->select('outlet_stocks.id as id', 'medicines.category_id as category_id', 'outlet_stocks.size as size', 'medicines.medicine_name as medicine_name', 'medicines.id as medicine_id')->where('medicine_name', 'like', '%' . $search . '%')->get();
        }

        $response = array();
        foreach ($medicines as $medicine) {
            $category = Category::where('id', $medicine->category_id)->first();
            $response[] = array(
                "id" => $medicine->id,
                "text" => $medicine->medicine_name . ' - ' . $category->category_name . ' - ' . '  ' . $medicine->size,
            );
        }
        return response()->json($response);
    }

    public function get_medicine_details_for_sale($id)
    {

        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
        if (strlen($id) >= 10) {
            $product_details = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $outlet_id)->where('outlet_stocks.barcode_text', '=', $id)
                ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
                ->select('outlet_stocks.*', 'medicines.medicine_name as medicine_name')->first();
        } else {
            $product_details = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $outlet_id)->where('outlet_stocks.id', '=', $id)
                ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
                ->select('outlet_stocks.*', 'medicines.medicine_name as medicine_name')->first();
        }


        // $product_details = Medicine::where('id', $id)->select('id','medicine_name','price','manufacturer_price')->first();
        return json_encode($product_details);
    }

    public function printInvoice($id)
    {
        $outletInvoice = OutletInvoice::find($id);
        $outletInvoicedetails = OutletInvoiceDetails::where('outlet_invoice_id', $id)->get();
        return view('admin.invoice.print', compact('outletInvoice', 'outletInvoicedetails'));
    }

    public function print(Request $request)
    {

        $outletinvoice = OutletInvoice::where('outlet_id', $request->outlet_id)->orderBy('id', 'desc')->first();

        return response()->json([
            'data' => $outletinvoice
        ]);
    }


    public function dueList()
    {
        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');

        if (Auth::user()->hasRole('Super Admin')) {

            $datas = OutletInvoice::where('due_amount', '>', '0')->whereDate('sale_date', '>=', Carbon::now()->month())->orderby('id', 'desc')->get();
            return view('admin.Pos.sale_due_index', compact('datas'));
        } else {

            $datas = OutletInvoice::where('due_amount', '>', '0')->whereDate('sale_date', '>=', Carbon::now()->month())->where('outlet_id', $outlet_id)->orderby('id', 'desc')->get();
            return view('admin.Pos.sale_due_index', compact('datas'));
        }
    }


    public function payDue($id)
    {
        $payment = PaymentMethod::pluck('method_name', 'id');
        $outletInvoice = OutletInvoice::findOrFail($id);
        $outletInvoiceDetails = OutletInvoiceDetails::where('outlet_invoice_id', $id)->get();
        $saledetails = OutletPayment::where('invoice_id', $id)->get();
        return view('admin.Pos.due_payment', compact('outletInvoice', 'outletInvoiceDetails', 'saledetails', 'payment'));
    }

    public function paymentDue(Request $request)
    {

        $invoiceData = OutletInvoice::where('id', $request->outlet_invoice_id)->first();
        $customer = Customer::where('id', $invoiceData->customer_id)->first();

        try {
            $arra = array(
                'total_discount' => $invoiceData->total_discount + $request->discount,
                'paid_amount' => $invoiceData->paid_amount + $request->paid_amount,
                'due_amount' => $invoiceData->due_amount - ($request->discount + $request->paid_amount),

            );

            $due = array(
                'due_balance' => $customer->due_balance - $request->paid_amount,
            );

            $array2 = array(

                'amount' => $request->total,
                'pay' => $request->paid_amount,
                'due' => $request->due,
                'customer_id' => $invoiceData->customer_id,
                'invoice_id' => $invoiceData->id,
                'payment_method_id' => $request->payment_method_id,
                'outlet_id' => $invoiceData->outlet_id,
                'collected_by' => Auth::user()->id,


            );
            OutletPayment::create($array2);
            CustomerDuePayment::create([
                'outlet_id' => $invoiceData->outlet_id,
                'customer_id' => $invoiceData->customer_id,
                'due_amount' => $request->total,
                'pay' => $request->paid_amount,
                'rest_amount' => $request->total - $request->paid_amount,
                'received_by' => Auth::user()->id,
            ]);
            Customer::where('id', $invoiceData->customer_id)->update($due);
            OutletInvoice::where('id', $request->outlet_invoice_id)->update($arra);
            return redirect()->back()->with('success', 'Due Payment Successful.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ajaxInvoice(Request $request)
    {
        // Extract datatable parameters with defaults
        $params = [
            'draw' => $request->get('draw'),
            'start' => $request->get('start', 0),
            'length' => $request->get('length', 10),
            'search' => $request->get('search')['value'] ?? '',
            'column' => $request->get('columns')[$request->get('order')[0]['column'] ?? 0]['data'] ?? 'id',
            'direction' => $request->get('order')[0]['dir'] ?? 'asc'
        ];

        // Build base query with eager loading
        $query = OutletInvoice::with(['customer:id,mobile', 'outlet:id,outlet_name'])
            ->select([
                'id',
                'sale_date',
                'outlet_id',
                'customer_id',
                'payment_method_id',
                'total_with_charge',
                'paid_amount',
                'added_by',
                'grand_total'
            ])
            ->when(!auth()->user()->hasRole(['Super Admin', 'Admin']), function ($query) {
                return $query->where('outlet_id', auth()->user()->outlet_id);
            });

        // Apply search if provided
        if ($params['search']) {
            $query->where(function ($q) use ($params) {
                $search = $params['search'];
                $q->whereHas('customer', fn($q) => $q->where('mobile', 'like', "%{$search}%"))
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereDate('sale_date', 'like', "%{$search}%");
            });
        }

        // Get total records count
        $totalRecords = $query->count();

        // Get paginated and sorted data
        $invoices = $query->orderBy($params['column'], $params['direction'])
            ->offset($params['start'])
            ->limit($params['length'])
            ->get();

        // Transform data for response
        $data = $invoices->map(function ($invoice) {
            $print = route('print-invoice', $invoice->id);
            $details = route('sale.details', $invoice->id);
            $return = route('payDue', $invoice->id);

            $action = $invoice->paid_amount < $invoice->grand_total
                ? '<a href="' . $print . '" target="_blank" class="btn btn-danger btn-xs" title="Print" style="margin-right:3px"><i class="fa fa-print" aria-hidden="true"></i></a>
                   <a href="' . $details . '" class="btn btn-primary btn-xs" title="Details" style="margin-right:3px"><i class="fa fa-info" aria-hidden="true"></i></a>
                   <a href="' . $return . '" class="btn btn-success btn-xs" title="Return" style="margin-right:3px"><i class="fa fa-paypal" aria-hidden="true"></i></a>'
                : '<a href="' . $print . '" target="_blank" class="btn btn-danger btn-xs" title="Print" style="margin-right:3px"><i class="fa fa-print" aria-hidden="true"></i></a>
                   <a href="' . $details . '" class="btn btn-primary btn-xs" title="Details" style="margin-right:3px"><i class="fa fa-info" aria-hidden="true"></i></a>';

            return [
                'id' => $invoice->id,
                'sale_date' => $invoice->sale_date,
                'outlet_name' => $invoice->outlet->outlet_name,
                'mobile' => $invoice->customer->mobile ?? '',
                'payment_method_id' => PaymentMethod::getPayment($invoice->payment_method_id),
                'grand_total' => $invoice->total_with_charge,
                'paid_amount' => $invoice->paid_amount,
                'sold_by' => User::find($invoice->added_by)->name ?? '',
                'action' => $action
            ];
        });

        return response()->json([
            'draw' => (int) $params['draw'],
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecords,
            'aaData' => $data
        ]);
    }
}
