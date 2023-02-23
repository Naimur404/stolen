<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Outlet;
use App\Models\OutletHasUser;
use App\Models\OutletInvoice;
use App\Models\OutletInvoiceDetails;
use App\Models\OutletStock;
use App\Models\PaymentMethod;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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

    }

    public function index()


    {
        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');

        if (Auth::user()->hasRole('Super Admin')) {

            $datas = OutletInvoice::whereDate('sale_date', '>=', Carbon::now()->month())->orderby('id', 'desc')->get();
            return view('admin.Pos.index_pos', compact('datas'));
        } else {

            $datas = OutletInvoice::whereDate('sale_date', '>=', Carbon::now()->month())->where('outlet_id', $outlet_id)->orderby('id', 'desc')->get();
            return view('admin.Pos.index_pos', compact('datas'));

        }

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
            'expiry_date' => 'required',
            'quantity' => 'required',
            'box_mrp' => 'required',
            'grand_total' => 'required',
            'payment_method_id' => 'required',


        ]);

        if($request->mobile == ''){
            $customer = Customer::where('outlet_id', $request->outlet_id)->where('mobile','LIKE','%00000%')->first();

        }else{


            $customerCheck = Customer::where('mobile', $request->mobile)->first();


            if (is_null($customerCheck)) {
                $customerdetails = array(
                    'name' => $input['name'],
                    'mobile' => $input['mobile'],
                    'address' => $input['address'],
                    'outlet_id' => $input['outlet_id'],
                    'points' => round(($input['grand_total'] / 100), 2),

                );
                $customer = Customer::create($customerdetails);

            } else {
                $customerdetails = array(
                    'name' => $input['name'],
                    'mobile' => $input['mobile'],

                    'address' => $input['address'],
                    'points' => round(($input['grand_total'] / 100), 2) + $customerCheck->points,

                );
                $customer = Customer::where('mobile', $request->mobile)->first();
                Customer::where('mobile', $request->mobile)->update($customerdetails);

            }

        }


        $invoice = array(

            'outlet_id' => $input['outlet_id'],
            'customer_id' => $customer->id,
            'sale_date' => Carbon::now(),
            'sub_total' => $input['sub_total'],
            'vat' => $input['vat'],
            'total_discount' => $input['discount'],
            'grand_total' => $input['grand_total'],
            'paid_amount' => $input['paid_amount'],
            'due_amount' => $input['due_amount'],
            'earn_point' => round(($input['grand_total'] / 100), 2),
            'payment_method_id' => $input['payment_method_id'],
            'added_by' => Auth::user()->id,

        );
        try {


            $outletinvoice = OutletInvoice::create($invoice);
            $medicines = $input['product_name'];

            for ($i = 0; $i < sizeof($medicines); $i++) {
                $invoicedetails = array(

                    'outlet_invoice_id' => $outletinvoice->id,

                    'medicine_id' => $input['product_id'][$i],
                    'medicine_name' => $input['product_name'][$i],
                    'expiry_date' => $input['expiry_date'][$i],

                    'available_qty' => $input['stockquantity'][$i],

                    'quantity' => $input['quantity'][$i],
                    'rate' => $input['box_mrp'][$i],
                    'discount' => $input['totaldis'][$i],
                    'total_price' => $input['total'][$i],

                );
                OutletInvoiceDetails::create($invoicedetails);


                $findquantity = OutletStock::where('outlet_id', $input['outlet_id'])->where('medicine_id', $input['product_id'][$i])->whereDate('expiry_date', '=', $input['expiry_date'][$i])->first();


                $stock2 = array(

                    'quantity' => (int)$findquantity->quantity - (int)$input['quantity'][$i],
                );

                OutletStock::where('outlet_id', $input['outlet_id'])->where('medicine_id', $input['product_id'][$i])->whereDate('expiry_date', '=', $input['expiry_date'][$i])->update($stock2);


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
                ->select('outlet_stocks.medicine_id as id', 'outlet_stocks.expiry_date as expiry_date', 'medicines.medicine_name as medicine_name', 'medicines.id as medicine_id')->limit(20)->get();

        } else {

            $medicines = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $outlet_id)->where('outlet_stocks.quantity', '>', '0')
                ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
                ->select('outlet_stocks.medicine_id as id', 'outlet_stocks.expiry_date as expiry_date', 'medicines.medicine_name as medicine_name', 'medicines.id as medicine_id')->where('medicine_name', 'like', '%' . $search . '%')->get();


        }

        $response = array();
        foreach ($medicines as $medicine) {
            $response[] = array(
                "id" => $medicine->medicine_id,
                "text" => $medicine->medicine_name . ' - ' . ' EX ' . $medicine->expiry_date,
            );
        }
        return response()->json($response);
    }

    public function get_medicine_details_for_sale($id)
    {

        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
        $product_details = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $outlet_id)->where('outlet_stocks.medicine_id', '=', $id)
            ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
            ->select('outlet_stocks.*', 'medicines.medicine_name as medicine_name')->first();

        // $product_details = Medicine::where('id', $id)->select('id','medicine_name','price','manufacturer_price')->first();
        return json_encode($product_details);
    }

    public function printInvoice($id)
    {

        $outletInvoice = OutletInvoice::find($id);
        $outletInvoicedetails = OutletInvoiceDetails::where('outlet_invoice_id', $id)->get();
        return view('admin.invoice.print', compact('outletInvoice', 'outletInvoicedetails'));


    }

    public function print(Request $request){

        $outletinvoice = OutletInvoice::where('outlet_id',$request->outlet_id)->orderBy('id','desc')->first();

              return response()->json([
                'data' => $outletinvoice
            ]);
    }
}
