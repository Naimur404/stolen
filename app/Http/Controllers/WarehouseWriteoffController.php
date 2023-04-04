<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\WarehouseWriteoff;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseWriteoffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {

        $this->middleware('permission:warehouse-writeoff', ['only' => ['index','store','show']]);


    }

    public function index()
    {
        $datas = WarehouseWriteoff::all();
        return view('admin.Writeoff.warehouse_index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $warehouse = Warehouse::pluck('warehouse_name', 'id');
        return view('admin.Writeoff.warehouse_writeoff',compact('warehouse'));
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

        try{

            $data = array(
                'warehouse_id' => $input['warehouse_id'],
                'warehouse_stock_id'=> $input['stock_id'],
                'medicine_id'=> $input['medicine'],
                'medicine_name'=> $input['medicine_name'],
                'previous_stock'=> $input['pre_quantity'],
                'quantity'=> $input['quantity'],
                'reason'=> $input['reason'],
                'remarks'=> $input['remarks'],
                'added_by'=> Auth::user()->id,
            );

            WarehouseWriteoff::create($data);
            $data1 = array(
             'quantity' =>  $input['pre_quantity'] - $input['quantity'],

            );
             WarehouseStock::where('id', $input['stock_id'])->where('warehouse_id',$input['warehouse_id'])->where('medicine_id',$input['medicine'])->update($data1);
             return redirect()->back()->with('success', 'WriteOff Added Sucessfully.');
        }catch(Exception $e){
            return redirect()->route('warehouse-writeoff.create')->with('success', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WarehouseWriteoff  $warehouseWriteoff
     * @return \Illuminate\Http\Response
     */
    public function show(WarehouseWriteoff $warehouseWriteoff)
    {
        return view('admin.Writeoff.show_warehouse',compact('warehouseWriteoff'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WarehouseWriteoff  $warehouseWriteoff
     * @return \Illuminate\Http\Response
     */
    public function edit(WarehouseWriteoff $warehouseWriteoff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WarehouseWriteoff  $warehouseWriteoff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WarehouseWriteoff $warehouseWriteoff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WarehouseWriteoff  $warehouseWriteoff
     * @return \Illuminate\Http\Response
     */
    public function destroy(WarehouseWriteoff $warehouseWriteoff)
    {
        //
    }
}
