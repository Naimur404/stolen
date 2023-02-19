<?php

namespace App\Http\Controllers;

use App\Models\OutletInvoice;
use App\Models\OutletInvoiceDetails;
use App\Models\SalesReturn;
use Illuminate\Http\Request;

class SalesReturnController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {  

        $data = OutletInvoice::find($id);
        $medicinedetails = OutletInvoiceDetails::where('outlet_invoice_id',$data->id)->get();
       
         return view('admin.Pos.sales_retun',compact('data','medicinedetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return \Illuminate\Http\Response
     */
    public function edit(SalesReturn $salesReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesReturn $salesReturn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesReturn $salesReturn)
    {
        //
    }
}
