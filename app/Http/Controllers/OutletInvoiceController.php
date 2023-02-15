<?php

namespace App\Http\Controllers;

use App\Models\OutletHasUser;
use App\Models\OutletInvoice;
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
    public function index()
    {
        $outlet_id = OutletHasUser::where('user_id', Auth::user()->id)->first();
        return view('admin.Pos.pos',compact('outlet_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dump($request->all());
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
