<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\Warehouse;
use App\Models\WarehouseReturn;
use App\Models\WarehouseReturnDetails;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $outlet_id = Auth::user()->outlet_id != null  ?  Auth::user()->outlet_id : Outlet::orderby('id','desc')->first('id');
        if (Auth::user()->hasRole('Super Admin')){
        $warehousereturns = WarehouseReturn::get();
        }else{
            $warehousereturns = WarehouseReturn::where('outlet_id',$outlet_id)->get();
        }
        return view('admin.warehousereturn.index', compact('warehousereturns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $warehouse = Warehouse::pluck('warehouse_name', 'id');
        return view('admin.warehousereturn.create',compact('warehouse'));
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

            $WarehouseReturn = WarehouseReturn::create($purchase_input);

            $medicines = $input['product_name'];

            for ($i = 0; $i < sizeof($medicines); $i++) {
                $return_details = array(

                    'warehouse_return_id' => $WarehouseReturn->id,
                    'medicine_id' => $input['product_id'][$i],
                    'medicine_name' => $input['product_name'][$i],

                    'quantity' => $input['quantity'][$i],

                    'expiry_date' => $input['expiry_date'][$i],


                    // 'rate' => $input['total_price'][$i] / $input['quantity'][$i],



                );
                // $warehousetock = WarehouseStock::where('warehouse_id', $input['warehouse_id'])->where('medicine_id',$input['product_id'][$i])->whereDate('expiry_date','=',$input['expiry_date'][$i])->implode('quantity');
                // $new_stock = array(
                //       'quantity' => $warehousetock - $input['quantity'][$i] ,

                // );
                // WarehouseStock::where('warehouse_id', $input['warehouse_id'])->where('medicine_id',$input['product_id'][$i])->update($new_stock);



                WarehouseReturnDetails::create($return_details);
            }


            return redirect()->back()->with('success', 'Request has been Sent.');
        } catch (Exception $e) {

             return redirect()->back()->with('error', $e->getMessage());
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WarehouseReturn  $warehouseReturn
     * @return \Illuminate\Http\Response
     */
    public function show(WarehouseReturn $warehouseReturn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WarehouseReturn  $warehouseReturn
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = WarehouseReturn::find($id);

        $returndetails = WarehouseReturnDetails::where('warehouse_return_id',$data->id)->get();
        $warehouse = Warehouse::pluck('warehouse_name', 'id');
        return view('admin.warehousereturn.edit',compact('data','returndetails','warehouse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WarehouseReturn  $warehouseReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        try{
           WarehouseReturn::where('id',$id)->update($purchase_input);
            $medicines = $input['product_name'];

            for ($i = 0; $i < sizeof($medicines); $i++) {

                $return_details = array(

                    'warehouse_return_id' => $id,
                    'medicine_id' => $input['product_id'][$i],
                    'medicine_name' => $input['product_name'][$i],

                    'quantity' => $input['quantity'][$i],

                    'expiry_date' => $input['expiry_date'][$i],






                );
                $check = WarehouseReturnDetails::where('warehouse_return_id',$id)->where('medicine_id',$input['product_id'][$i])->first();


                 if($check != null){


                    WarehouseReturnDetails::where('warehouse_return_id',$id)->where('medicine_id',$input['product_id'][$i])->update($return_details);

                 }else{

                    WarehouseReturnDetails::create($return_details);


                 }


            }


            return redirect()->back()->with('success', 'Data has been Updated.');
        } catch (Exception $e) {

             return redirect()->back()->with('error', $e->getMessage());
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WarehouseReturn  $warehouseReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        WarehouseReturn::where('id',$id)->delete();
        WarehouseReturnDetails::where('warehouse_return_id',$id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted');
    }
    public function medicineReturnlDelete($medicine_id, $return_id)
    {
      $data =  WarehouseReturnDetails::where('medicine_id',$medicine_id)->where('warehouse_return_id', $return_id)->delete();


        return redirect()->back()->with('success', 'Data has been Deleted.');
    }
}
