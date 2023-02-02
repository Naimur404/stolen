<?php

namespace App\Http\Controllers;

use App\Models\OutletCheckIn;
use App\Models\OutletStock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Output\Output;

class OutletStockController extends Controller
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
        $stock =  OutletStock::create($input);
          try{
            $data = array(
              'outlet_id'    => $stock->outlet_id,
              'medicine_distribute_id'    => $request->medicine_distribute_id,
              'medicine_id'    => $stock->medicine_id,
              'expiry_date'    => $stock->expiry_date,
              'quantity'    => $stock->quantity,
               'checked_by' => Auth::user()->id,
               'remarks'    => 'added',

            );
              OutletCheckIn::create($data);

           return redirect()->back()->with('success', ' Successfully Added.');
        }catch(Exception $e){
           return redirect()->route('distribute-medicine.index')->with('success', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OutletStock  $outletStock
     * @return \Illuminate\Http\Response
     */
    public function show(OutletStock $outletStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OutletStock  $outletStock
     * @return \Illuminate\Http\Response
     */
    public function edit(OutletStock $outletStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OutletStock  $outletStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutletStock $outletStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OutletStock  $outletStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutletStock $outletStock)
    {
        //
    }
}
