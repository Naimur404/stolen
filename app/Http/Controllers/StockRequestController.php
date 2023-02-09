<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\OutletHasUser;
use App\Models\StockRequest;
use App\Models\StockRequestDetails;
use App\Models\Warehouse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
    {
        $this->middleware('permission:sent_stock_request', ['only' => ['index','store','update','destroy','edit','stockRequestDelete','details']]);
        $this->middleware('permission:stock_request', ['only' => ['warehouseRequest','store','hasSent','hasAccepted','hasAcceptedMedicine','detailsRequestWarehouse']]);

    }
    public function index()
    {
    $data = OutletHasUser::where('user_id', Auth::user()->id)->first();
    $outlet = Outlet::where('id',$data->outlet_id)->first();
    $stockrequets = StockRequest::where('outlet_id',$outlet->id)->get();

    return view('admin.stockrequest.outlet_stock_request_index', compact('stockrequets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = OutletHasUser::where('user_id', Auth::user()->id)->first();
        $outlet = Outlet::where('id',$data->outlet_id)->pluck('outlet_name','id');
        $warehouse = Warehouse::pluck('warehouse_name','id');
        return view('admin.stockrequest.outlet_request_to_warehouse',compact('outlet','warehouse'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required',
            'warehouse_id' => 'required'
        ]);



        $input=$request->all();

        // return $input;



        $purchase_input = [
            'warehouse_id' => $input['warehouse_id'],
            'outlet_id' => $input['outlet_id'],
            'date' => $input['purchase_date'],

            'added_by' => Auth::user()->id,

            'remarks' => $input['remarks'],


        ];
        try {

            $medicinestock = StockRequest::create($purchase_input);

            $medicines = $input['product_name'];

            for ($i = 0; $i < sizeof($medicines); $i++) {
                $return_details = array(

                    'stock_request_id' => $medicinestock->id,
                    'medicine_id' => $input['product_id'][$i],
                    'medicine_name' => $input['product_name'][$i],

                    'quantity' => $input['quantity'][$i],




                    // 'rate' => $input['total_price'][$i] / $input['quantity'][$i],



                );
                // $warehousetock = WarehouseStock::where('warehouse_id', $input['warehouse_id'])->where('medicine_id',$input['product_id'][$i])->whereDate('expiry_date','=',$input['expiry_date'][$i])->implode('quantity');
                // $new_stock = array(
                //       'quantity' => $warehousetock - $input['quantity'][$i] ,

                // );
                // WarehouseStock::where('warehouse_id', $input['warehouse_id'])->where('medicine_id',$input['product_id'][$i])->update($new_stock);



                StockRequestDetails::create($return_details);
            }


            return redirect()->back()->with('success', ' Stock Request has been Sent.');
        } catch (Exception $e) {

             return redirect()->back()->with('error', $e->getMessage());
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockRequest  $stockRequest
     * @return \Illuminate\Http\Response
     */
    public function show(StockRequest $stockRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockRequest  $stockRequest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = OutletHasUser::where('user_id', Auth::user()->id)->first();
        $outlet = Outlet::where('id',$data->outlet_id)->pluck('outlet_name','id');
        $data = StockRequest::find($id);

        $stockdetails = StockRequestDetails::where('stock_request_id',$data->id)->get();
        $warehouse = Warehouse::pluck('warehouse_name', 'id');
        return view('admin.stockrequest.outlet_stock_request_edit',compact('data','stockdetails','warehouse','outlet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockRequest  $stockRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required',
            'warehouse_id' => 'required'
        ]);



        $input=$request->all();

        // return $input;



        $purchase_input = [
            'warehouse_id' => $input['warehouse_id'],
            'outlet_id' => $input['outlet_id'],
            'date' => $input['purchase_date'],



            'remarks' => $input['remarks'],


        ];
        try {
              StockRequest::where('id',$request->id)->update($purchase_input);


            $medicines = $input['product_name'];

            for ($i = 0; $i < sizeof($medicines); $i++) {
                $return_details = array(

                    'stock_request_id' => $request->id,
                    'medicine_id' => $input['product_id'][$i],
                    'medicine_name' => $input['product_name'][$i],

                    'quantity' => $input['quantity'][$i],




                );
                $check = StockRequestDetails::where('stock_request_id',$request->id)->where('medicine_id',$input['product_id'][$i])->first();



                if($check != null){


                    StockRequestDetails::where('stock_request_id',$request->id)->where('medicine_id',$input['product_id'][$i])->update($return_details);

                }else{

                    StockRequestDetails::create($return_details);


                 }




            }


            return redirect()->back()->with('success', ' Stock Request has been Updated.');
        } catch (Exception $e) {

             return redirect()->back()->with('error', $e->getMessage());
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockRequest  $stockRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        StockRequest::where('id',$id)->delete();
        StockRequestDetails::where('stock_request_id',$id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted');
    }
    public function stockRequestDelete($medicine_id,$request_id ){
        $data =  StockRequestDetails::where('medicine_id',$medicine_id)->where('stock_request_id', $request_id)->delete();


        return redirect()->back()->with('success', 'Data has been Deleted.');
    }
    public function details($id){
        $stockrequest = StockRequest::find($id);
        $stockDetails  = StockRequestDetails::where('stock_request_id',$id)->get();
        return view('admin.stockrequest.stock_request_details_outlet',compact('stockrequest','stockDetails'));

    }
    public function warehouseRequest(){

    $stockrequets = StockRequest::all();

    return view('admin.stockrequest.warehouse_stock_request', compact('stockrequets'));

    }
    public function hasSent($id,$status){

        $data = StockRequest::find($id);
        $data->has_sent = $status;
        $data->save();
        return redirect()->back()->with('success','Sent Status Updated');

    }
    public function hasAccepted($id,$status){
        $data = array(
             'has_accepted' => $status,
             'accepted_by' => Auth::user()->id,
             'accepted_on' => Carbon::now()

        );

        StockRequest::where('id',$id)->update($data);
        $status = array (
              'has_accepted' => $status,

        );
        StockRequestDetails::where('stock_request_id',$id)->update($status);


        return redirect()->back()->with('success','Accepted Status Updated');

    }

    public function hasAcceptedMedicine($id,$status,$medicine_id){

        $status = array (
            'has_accepted' => $status,

      );
      StockRequestDetails::where('stock_request_id',$id)->where('medicine_id',$medicine_id)->update($status);
      return redirect()->back()->with('success','Accepted Status Updated For This Medicine');

    }
    public function detailsRequestWarehouse($id){
        $stockrequest = StockRequest::find($id);
        $stockDetails  = StockRequestDetails::where('stock_request_id',$id)->get();
        return view('admin.stockrequest.details_for_warehouse',compact('stockrequest','stockDetails'));

    }

}
