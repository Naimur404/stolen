<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\OutletStock;
use App\Models\OutletWriteoff;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutletWriteoffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */

     function __construct()
     {

         $this->middleware('permission:outlet-writeoff', ['only' => ['index','store','show']]);


     }
    public function index()
    {
        $datas = OutletWriteoff::all();
        return view('admin.Writeoff.outlet_index',compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (Auth::user()->hasRole('Super Admin')) {
        $outlets = Outlet::pluck('outlet_name', 'id');
    }else{
        $outlet_id = Auth::user()->outlet_id != null  ?  Auth::user()->outlet_id : Outlet::orderby('id','desc')->first('id');
        $outlets = Outlet::where('id',$outlet_id)->pluck('outlet_name', 'id');
    }
        return view('admin.Writeoff.outlet_writeoff',compact('outlets'));
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
                'outlet_id' => $input['outlet_id'],
                'outlet_stock_id'=> $input['stock_id'],
                'medicine_id'=> $input['medicine'],
                'medicine_name'=> $input['medicine_name'],
                'previous_stock'=> $input['pre_quantity'],
                'quantity'=> $input['quantity'],
                'reason'=> $input['reason'],
                'remarks'=> $input['remarks'],
                'added_by'=> Auth::user()->id,
            );

            OutletWriteoff::create($data);
            $data1 = array(
             'quantity' =>  $input['pre_quantity'] - $input['quantity'],

            );
             OutletStock::where('id', $input['stock_id'])->where('outlet_id',$input['outlet_id'])->where('medicine_id',$input['medicine'])->update($data1);
             return redirect()->back()->with('success', ' WriteOff Added Sucessfully.');
        }catch(Exception $e){
            return redirect()->route('outlet-writeoff.create')->with('success', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OutletWriteoff  $outletWriteoff
     * @return \Illuminate\Http\Response
     */
    public function show(OutletWriteoff $outletWriteoff)
    {
        return view('admin.Writeoff.edit_outlet_writeoff', compact('outletWriteoff'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OutletWriteoff  $outletWriteoff
     * @return \Illuminate\Http\Response
     */
    public function edit(OutletWriteoff $outletWriteoff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OutletWriteoff  $outletWriteoff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutletWriteoff $outletWriteoff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OutletWriteoff  $outletWriteoff
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutletWriteoff $outletWriteoff)
    {
        //
    }
}
