<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Outlet;
use App\Models\OutletHasUser;
use App\Models\OutletInvoice;
use App\Models\OutletInvoiceDetails;
use App\Models\OutletPayment;
use App\Models\OutletStock;
use App\Models\PaymentMethod;
use App\Models\RedeemPointLog;
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

        $this->middleware('permission:pay-due.payment', ['only' => ['dueList','payDue','paymentDue']]);

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

        if($request->mobile == '' || $request->mobile == null){
            $customer = Customer::where('outlet_id', $request->outlet_id)->where('mobile','LIKE','%00000%')->first();
            if (is_null($customer)) {

                $customerdetails = array(
                    'name' => 'Walking Customer',
                    'mobile' => '00000000000',
                    'address' => '',
                    'outlet_id' => $input['outlet_id'],
                    'points' => '0',

                );
                $customer = Customer::create($customerdetails);
            }


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
                $points = $customerCheck->points;
                if($request->redeem_points > 0){

                  $points = $customerCheck->points - $request->redeem_points;

                  }
                $customerdetails = array(
                    'name' => $input['name'],
                    'mobile' => $input['mobile'],

                    'address' => $input['address'],
                    'points' => round(($input['grand_total'] / 100), 2) + $points,

                );
                $customer = Customer::where('mobile', $request->mobile)->first();
                Customer::where('mobile', $request->mobile)->update($customerdetails);

            }

        }
        $discount = 0;
        if($request->discount > 0 && $request->flatdiscount > 0){
            $discount = $request->discount + $request->flatdiscount;
        }elseif($request->discount == 0 && $request->flatdiscount == 0){

            $discount = 0;
        }

            else{
            if($request->discount > 0){
                $discount = $request->discount;
              }else{

              }
              if($request->flatdiscount > 0){
                $discount = $request->flatdiscount;
              }

        }


        $invoice = array(

            'outlet_id' => $input['outlet_id'],
            'customer_id' => $customer->id,
            'sale_date' => Carbon::now(),
            'sub_total' => $input['sub_total'],
            'vat' => $input['vat'],
            'total_discount' => $discount,
            'grand_total' => $input['grand_total'],
            'paid_amount' => $input['paid_amount'],
            'due_amount' => $input['due_amount'],
            'redeem_point' => $input['redeem_points'],
            'earn_point' => round(($input['grand_total'] / 100), 2),
            'payment_method_id' => $input['payment_method_id'],
            'added_by' => Auth::user()->id,

        );
        try {


            $outletinvoice = OutletInvoice::create($invoice);


            if(isset($request->redeem_points) && ($request->redeem_points > 0)){

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
                ->select('outlet_stocks.medicine_id as id','medicines.category_id as category_id', 'outlet_stocks.expiry_date as expiry_date', 'medicines.medicine_name as medicine_name', 'medicines.id as medicine_id')->limit(20)->get();

        } else {

            $medicines = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $outlet_id)->where('outlet_stocks.quantity', '>', '0')
                ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
                ->select('outlet_stocks.medicine_id as id','medicines.category_id as category_id', 'outlet_stocks.expiry_date as expiry_date', 'medicines.medicine_name as medicine_name', 'medicines.id as medicine_id')->where('medicine_name', 'like', '%' . $search . '%')->get();


        }

        $response = array();
        foreach($medicines as $medicine){
            $category = Category::where('id',$medicine->category_id)->first();
           $response[] = array(
                "id"=>$medicine->medicine_id.','.$medicine->expiry_date,
                "text"=>$medicine->medicine_name.' - '.$category->category_name.' - '.' EX '.$medicine->expiry_date,
           );
        }
        return response()->json($response);
    }

    public function get_medicine_details_for_sale($id)
    {
        $data = explode(",", $id);
        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
        $product_details = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $outlet_id)->where('outlet_stocks.medicine_id', '=', $data[0])->whereDate('expiry_date','=',$data[1])
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


    public function dueList()


    {
        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');

        if (Auth::user()->hasRole('Super Admin')) {

            $datas = OutletInvoice::where('due_amount','>','0')->whereDate('sale_date', '>=', Carbon::now()->month())->orderby('id', 'desc')->get();
            return view('admin.Pos.sale_due_index', compact('datas'));
        } else {

            $datas = OutletInvoice::where('due_amount','>','0')->whereDate('sale_date', '>=', Carbon::now()->month())->where('outlet_id', $outlet_id)->orderby('id', 'desc')->get();
            return view('admin.Pos.sale_due_index', compact('datas'));

        }

    }



    public function payDue($id)


    {
        $payment = PaymentMethod::pluck('method_name','id');
        $outletInvoice = OutletInvoice::findOrFail($id);
        $outletInvoiceDetails = OutletInvoiceDetails::where('outlet_invoice_id', $id)->get();
        $saledetails = OutletPayment::where('invoice_id',$id)->get();
        return view('admin.Pos.due_payment', compact('outletInvoice', 'outletInvoiceDetails','saledetails','payment'));

    }

    public function paymentDue(Request $request)


    {


        $invoiceData = OutletInvoice::where('id',$request->outlet_invoice_id)->first();


        try{
            $arra = array(
                'total_discount' => $invoiceData->total_discount + $request->discount,
                'paid_amount' => $invoiceData->paid_amount + $request->paid_amount,
                'due_amount'   => $invoiceData->due_amount - ($request->discount + $request->paid_amount),

             );

             $array2 = array(

                'amount' => $request->total,
                'pay' => $request->paid_amount,
                'due'   => $request->due,
                'customer_id'  => $invoiceData->customer_id,
                'invoice_id' => $invoiceData->id,
                'payment_method_id' => $request->payment_method_id,
                'outlet_id' => $invoiceData->outlet_id,
                'collected_by' => Auth::user()->id,


             );
             OutletPayment::create($array2);

         OutletInvoice::where('id',$request->outlet_invoice_id)->update($arra);
         return redirect()->back()->with('success', 'Due Payment Successfull.');

        }catch(Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }

    }
}
