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
use Illuminate\Support\Facades\Session;

class OutletInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
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

        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
        // $outlet_id = OutletHasUser::where('user_id', Auth::user()->id)->first();

        return view('admin.Pos.pos', compact('outlet_id', 'payment_methods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $input = $request->all();

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

        if ($request->mobile == '' || $request->mobile == null) {
            $customer = Customer::where('outlet_id', $request->outlet_id)->where('mobile', 'LIKE', '%00000%')->first();
            if (is_null($customer)) {

                $customerdetails = array(
                    'name' => 'Walking Customer',
                    'mobile' => '00000000000',
                    'address' => '',
                    'outlet_id' => $input['outlet_id'],
                    'points' => '0',

                );
                $customer = Customer::create($customerdetails);
            }else{
                $customerdetails = array(
                    'name' => 'Walking Customer',
                    'mobile' => '00000000000',
                    'address' => '',
                    'outlet_id' => $input['outlet_id'],
                    'points' => '0',

                );
                Customer::where('outlet_id', $request->outlet_id)->where('mobile', 'LIKE', '%00000%')->update($customerdetails);
            }
        } else {


            $customerCheck = Customer::where('mobile', $request->mobile)->first();


            if (is_null($customerCheck)) {
                $customerdetails = array(
                    'name' => $input['name'],
                    'mobile' => $input['mobile'],
                    'address' => $input['address'],
                    'birth_date' => Carbon::parse($input['birth_date'])->toDateString(),
                    'outlet_id' => $input['outlet_id'],
                    'due_balance' => $input['due_amount'],
                    'points' => round(($input['grand_total'] / 100), 2),

                );
                $customer = Customer::create($customerdetails);
            } else {
                $points = $customerCheck->points;
                if ($request->redeem_points > 0) {

                    $points = $customerCheck->points - $request->redeem_points;
                }
                $customer = Customer::where('mobile', $request->mobile)->first();
                $customerdetails = array(
                    'name' => $input['name'],
                    'mobile' => $input['mobile'],
                    'birth_date' => Carbon::parse($input['birth_date'])->toDateString(),
                    'due_balance' =>  round($customer->due_balance + $input['due_amount']),
                    'address' => $input['address'],
                    'points' => round(($input['grand_total'] / 100), 2) + $points,

                );

                Customer::where('mobile', $request->mobile)->update($customerdetails);
            }
        }
        $discount = 0;
        if ($request->discount > 0 && $request->flatdiscount > 0) {
            $discount = $request->discount + $request->flatdiscount;
        } elseif ($request->discount == 0 && $request->flatdiscount == 0) {

            $discount = 0;
        } else {
            if ($request->discount > 0) {
                $discount = $request->discount;
            } else {
            }
            if ($request->flatdiscount > 0) {
                $discount = $request->flatdiscount;
            }
        }


        $invoice = array(

            'outlet_id' => $input['outlet_id'],
            'customer_id' => $customer->id,
            'sale_date' => Carbon::now(),
            'sub_total' => $input['sub_total'] + round($request->discount),
            'vat' => round($input['vat']),
            'total_discount' => round($discount),
            'grand_total' => round($input['grand_total']),
            'given_amount' => round($input['paid_amount']),
            'payable_amount' => round($input['payable_amount']),
            'paid_amount' => $input['paid_amount'] > $input['payable_amount'] ? round($input['payable_amount']) : $input['paid_amount'],
            'due_amount' => round($input['due_amount']),
            'redeem_point' => $input['redeem_points'],
            'earn_point' => round(($input['grand_total'] / 100), 2),
            'payment_method_id' => $input['payment_method_id'],
            'added_by' => Auth::user()->id,

        );
        try {


            $outletinvoice = OutletInvoice::create($invoice);


            if (isset($request->redeem_points) && ($request->redeem_points > 0)) {

                $redeem = array(

                    'outlet_id' => $input['outlet_id'],
                    'customer_id' => $customer->id,
                    'invoice_id' => $outletinvoice->id,
                    'previous_point' => $customerCheck->points,
                    'redeem_point' => $request->redeem_points,

                );

                RedeemPointLog::create($redeem);
            }
            $medicines = $input['product_name'];

            for ($i = 0; $i < sizeof($medicines); $i++) {
                $invoicedetails = array(

                    'outlet_invoice_id' => $outletinvoice->id,
                    'stock_id' => $input['stock_id'][$i],
                    'medicine_id' => $input['product_id'][$i],
                    'medicine_name' => $input['product_name'][$i],
                    'size' => $input['size'][$i],

                    'available_qty' => $input['stockquantity'][$i],

                    'quantity' => $input['quantity'][$i],
                    'rate' => $input['box_mrp'][$i],
                    'discount' => round($input['totaldis'][$i]),
                    'total_price' => round($input['total'][$i]) + round($input['totaldis'][$i]),

                );
                OutletInvoiceDetails::create($invoicedetails);


                $findquantity = OutletStock::where('outlet_id', $input['outlet_id'])->where('medicine_id', $input['product_id'][$i])->where('size', '=', $input['size'][$i])->first();


                $stock2 = array(

                    'quantity' => (int)$findquantity->quantity - (int)$input['quantity'][$i],
                );

                OutletStock::where('outlet_id', $input['outlet_id'])->where('medicine_id', $input['product_id'][$i])->where('size', '=', $input['size'][$i])->update($stock2);
            }

            return response()->json([
                'data' => $outletinvoice
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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


        $draw = $request->get('draw');
        $start = $request->get("start");
        $row_per_page = $request->get("length"); // rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = (isset($columnIndex_arr[0]['column'])) ? $columnIndex_arr[0]['column'] : false; // column index
        $columnName = (isset($columnName_arr[$columnIndex]['data'])) ? $columnName_arr[$columnIndex]['data'] : false; // column name
        $columnSortOrder = (isset($order_arr[0]['dir'])) ? $order_arr[0]['dir'] : false; // asc or desc
        $searchValue = (isset($search_arr['value'])) ? $search_arr['value'] : false; // Search value

        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');

        // total records count

        if (auth()->user()->hasrole('Super Admin')) {
            $totalRecords = OutletInvoice::whereDate('sale_date', '>=', Carbon::now()->month())->orderBy('id', 'desc')->select('count(*) as allcount')->count();
            $invoices = DB::table('outlet_invoices')->whereDate('sale_date', '>=', Carbon::now()->month())
                ->leftJoin('customers', 'outlet_invoices.customer_id', '=', 'customers.id')->where('customers.mobile', 'like', '%' . $searchValue . '%')->orWhere('outlet_invoices.id', 'like', '%' . $searchValue . '%')->orWhereDate('outlet_invoices.sale_date', 'like', '%' . $searchValue . '%')->select('outlet_invoices.*', 'customers.mobile')
                ->orderBy($columnName, $columnSortOrder)
                ->skip($start)
                ->take($row_per_page)
                ->get();
        } else {
            $totalRecords = OutletInvoice::where('outlet_id', '=', $outlet_id)->whereDate('sale_date', '>=', Carbon::now()->month())->select('count(*) as allcount')->count();
            $invoices = DB::table('outlet_invoices')->orderBy($columnName, $columnSortOrder)->whereDate('sale_date', '>=', Carbon::now()->month())->where('outlet_invoices.outlet_id', $outlet_id)
                ->leftJoin('customers', 'outlet_invoices.customer_id', '=', 'customers.id')->where('customers.mobile', 'like', '%' . $searchValue . '%')->orWhere('outlet_invoices.id', 'like', '%' . $searchValue . '%')->orWhereDate('outlet_invoices.sale_date', 'like', '%' . $searchValue . '%')->select('outlet_invoices.*', 'customers.mobile')
                ->skip($start)
                ->take($row_per_page)
                ->get();
        }

        $total_record_switch_filter = $totalRecords;

        // fetch records with search

        $data_arr = array();

        $total = 0;
        foreach ($invoices as $invoice) {

            $print = route('print-invoice', $invoice->id);
            $return = route('sale-return.show', $invoice->id);
            $details = route('sale.details', $invoice->id);

            $s_no = $invoice->id;
            $sale_date = Carbon::parse($invoice->sale_date)->format('d-m-Y');
            $outlet_name = Outlet::getOutletName($invoice->outlet_id);
            $customer = $invoice->mobile;
            $payment = PaymentMethod::getPayment($invoice->payment_method_id);
            $total = $invoice->grand_total;
            $pay = $invoice->paid_amount;
            $sold_by = User::getUser($invoice->added_by);
            $action = '<a href="' . $print . '" target="_blank"class="btn btn-danger btn-xs" title="Print" style="margin-right:3px"><i class="fa fa-print" aria-hidden="true"></i></a>
<a href="' . $return . '"class="btn btn-success btn-xs" title="Return" style="margin-right:3px"><i class="fa fa-retweet" aria-hidden="true"></i></a><a href="' . $details . '"class="btn btn-primary btn-xs" title="Details"style="margin-right:3px"><i class="fa fa-info" aria-hidden="true"></i></a>';


            $data_arr[] = array(
                "id" => $s_no,
                "sale_date" => $sale_date,

                "outlet_name" => $outlet_name,
                "mobile" => $customer,
                "payment_method_id" => $payment,
                "grand_total" => $total,
                "paid_amount" => $pay,
                "sold_by" => $sold_by,
                "action" => $action,

            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $total_record_switch_filter,
            "aaData" => $data_arr,

        );

        return response()->json($response);
    }
}
