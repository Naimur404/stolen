<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OutletHasUser;
use App\Models\OutletInvoice;
use App\Models\OutletInvoiceDetails;
use App\Models\OutletStock;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class OutletInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
         $this->middleware('permission:invoice.management|invoice.create|invoice.edit|invoice.delete', ['only' => ['index','store']]);
         $this->middleware('permission:invoice.create', ['only' => ['create','store']]);
         $this->middleware('permission:invoice.edit', ['only' => ['edit','update']]);
         $this->middleware('permission:invoice.delete', ['only' => ['destroy']]);
     }
    public function index()


    {  $user  = User::find(Auth::user()->id);

        if ($user->hasRole('Super Admin')){

            $datas = OutletInvoice::all();
            return view('admin.Pos.index_pos',compact('datas'));
        }else{
        $outlet = OutletHasUser::where('user_id', Auth::user()->id)->first();
        $datas = OutletInvoice::where('outlet_id',$outlet->outlet_id)->get();
        return view('admin.Pos.index_pos',compact('datas'));

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $outlet_id = OutletHasUser::where('user_id', Auth::user()->id)->first();

         return view('admin.Pos.pos',compact('outlet_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $input = $request->all();

        $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|min:8',
            'outlet_id' => 'required',
            'product_id' => 'required',
            'product_name' => 'required',
            'expiry_date' => 'required',
            'quantity' => 'required',
            'box_mrp'  => 'required',
            'grand_total' => 'required',
            'payment_method_id' => 'required',



        ]);
        $customerCheck = Customer::where('mobile', $request->mobile)->first();


        if(is_null($customerCheck)){
            $customerdetails = array(
                'name' => $input['name'],
                'mobile' => $input['mobile'],
                'address' =>  $input['address'],
                'outlet_id' => $input['outlet_id'],
                'points'  => $input['points'],

               );
          $customer =  Customer::create($customerdetails);

        }else{
            $customerdetails = array(
                'name' => $input['name'],
                'mobile' => $input['mobile'],

                'address' =>  $input['address'],
                'points'  => $input['points'] + $customerCheck->points,

               );
            $customer =  Customer::where('mobile',$request->mobile)->first();
             Customer::where('mobile',$request->mobile)->update($customerdetails);

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
          'earn_point' => $input['points'],
          'payment_method_id' => $input['payment_method_id'],
          'added_by' => Auth::user()->id,

         );
try{



      $outletinvoice = OutletInvoice::create( $invoice);
    $medicines = $input['product_name'];

    for ($i = 0; $i < sizeof($medicines); $i++) {
  $invoicedetails = array(

'outlet_invoice_id' => $outletinvoice->id,

'medicine_id' => $input['product_id'][$i],
'medicine_name' => $input['product_name'][$i],
'expiry_date' => $input['expiry_date'][$i],

'available_qty' => $input['stockquantity'][$i],

'quantity' => $input['quantity'][$i],
'rate'  => $input['box_mrp'][$i],
'discount' => $input['totaldis'][$i],
'total_price' => $input['total'][$i],

  );
    OutletInvoiceDetails::create($invoicedetails);


    $findquantity = OutletStock::where('outlet_id', $input['outlet_id'])->where('medicine_id', $input['product_id'][$i])->whereDate('expiry_date','=',$input['expiry_date'][$i])->first();



        $stock2 = array(

            'quantity' => (int)$findquantity->quantity - (int)$input['quantity'][$i],
        );

        OutletStock::where('outlet_id', $input['outlet_id'])->where('medicine_id', $input['product_id'][$i])->whereDate('expiry_date','=',$input['expiry_date'][$i])->update($stock2);


}

return redirect()->back()->with('success', 'Data has been added.');
}catch(Exception $e){
    return redirect()->back()->with('error', $e->getMessage());
}


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OutletInvoice  $outletInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(OutletInvoice $outletInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OutletInvoice  $outletInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit(OutletInvoice $outletInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OutletInvoice  $outletInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutletInvoice $outletInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OutletInvoice  $outletInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutletInvoice $outletInvoice)
    {
        //
    }


    public function getoutletStock(Request $request){
        $outlet_id = OutletHasUser::where('user_id',Auth::user()->id)->first();

        $search = $request->search;
        if($search == ''){

            $medicines = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $outlet_id->outlet_id )->where('outlet_stocks.quantity' ,'>' ,'0')
            ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
            ->select('outlet_stocks.medicine_id as id','outlet_stocks.expiry_date as expiry_date','medicines.medicine_name as medicine_name','medicines.id as medicine_id')->get();

         }else{

            $medicines = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $outlet_id->outlet_id )->where('outlet_stocks.quantity' ,'>' ,'0')
            ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
            ->select('outlet_stocks.medicine_id as id','outlet_stocks.expiry_date as expiry_date','medicines.medicine_name as medicine_name' ,'medicines.id as medicine_id')->where('medicine_name', 'like', '%' .$search . '%')->get();



         }

         $response = array();
         foreach($medicines as $medicine){
            $response[] = array(
                 "id"=>$medicine->medicine_id .','.$medicine->expiry_date,
                 "text"=>$medicine->medicine_name.' - '.' EX '.$medicine->expiry_date,
            );
         }
         return response()->json($response);
      }

      public function get_medicine_details_for_sale($id){
        $string = $id;
        $str_arr = explode (",", $string);
        $outlet_id = OutletHasUser::where('user_id',Auth::user()->id)->first();
        $product_details = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $outlet_id->outlet_id )->where('outlet_stocks.expiry_date' ,'=' , $str_arr[1])->where('outlet_stocks.medicine_id' ,'=' , $str_arr[0])
        ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
        ->select('outlet_stocks.*','medicines.medicine_name as medicine_name')->first();

        // $product_details = Medicine::where('id', $id)->select('id','medicine_name','price','manufacturer_price')->first();
        return json_encode($product_details);
  }
}
