<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseCheckIn;
use App\Models\WarehouseStock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $input = $request->all();
      $stock =  WarehouseStock::create($input);
        try{
          $data = array(
            'warehouse_id'    => $stock->warehouse_id,
            'purchase_id'    => $request->purchase_id,
            'medicine_id'    => $stock->medicine_id,
            'expiry_date'    => $stock->expiry_date,
            'quantity'    => $stock->quantity,
             'checked_by' => Auth::user()->id,
             'remarks'    => 'added',

          );
            WarehouseCheckIn::create($data);

         return redirect()->back()->with('success', ' Successfully Added.');
      }catch(Exception $e){
         return redirect()->route('medicine-purchase.index')->with('success', $e->getMessage());
      }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WarehouseStock  $warehouseStock
     * @return \Illuminate\Http\Response
     */
    public function show(WarehouseStock $warehouseStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WarehouseStock  $warehouseStock
     * @return \Illuminate\Http\Response
     */
    public function edit(WarehouseStock $warehouseStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WarehouseStock  $warehouseStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WarehouseStock $warehouseStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WarehouseStock  $warehouseStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(WarehouseStock $warehouseStock)
    {
        //
    }
}
