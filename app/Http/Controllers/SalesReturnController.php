<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Outlet;
use App\Models\OutletInvoice;
use App\Models\OutletInvoiceDetails;
use App\Models\OutletPayment;
use App\Models\OutletStock;
use App\Models\PaymentMethod;
use App\Models\SalesReturn;
use App\Models\SalesReturnDetails;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Break_;

class SalesReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:sale-return.management|sale-return.store|sale-return.delete|sale-return.details|sale-return.show', ['only' => ['index', 'store']]);
        $this->middleware('permission:sale-return.store', ['only' => ['store']]);
        $this->middleware('permission:sale-return.show', ['only' => ['show']]);
        $this->middleware('permission:sale-return.delete', ['only' => ['destroy']]);
        $this->middleware('permission:sale-return.detail', ['only' => ['details']]);
    }
    public function index()
    {
        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');

        if (Auth::user()->hasRole('Super Admin')) {

            $datas = SalesReturn::whereDate('return_date', '>=', Carbon::now()->month())->orderby('id', 'desc')->get();
            return view('admin.Pos.sales_return_index', compact('datas'));
        } else {

            $datas = SalesReturn::whereDate('return_date', '>=', Carbon::now()->month())->where('outlet_id', $outlet_id)->orderby('id', 'desc')->get();
            return view('admin.Pos.sales_return_index', compact('datas'));
        }
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

        $points = round(($input['grand_total'] / 100), 2);
        $salesReturn = array(
            'return_date' => Carbon::now(),
            'outlet_id' => $input['outlet_id'],
            'customer_id' => $input['customer_id'],
            'invoice_id' => $input['invoice_id'],
            'sub_total' => $input['sub_total'],
            'deduct_amount' => $input['deduct_amount'],
            'grand_total'  => $input['grand_total'],
            'paid_amount' => $input['paid_amount'],
            'deduct_point' => $points,
            'due_amount' => $input['due_amount'],
            'payment_method_id' => $input['payment_method_id'],
            'added_by'   =>  Auth::user()->id,


        );

        try {

            $return = SalesReturn::create($salesReturn);
            $customer = Customer::where('id', $input['customer_id'])->first();
            $nowpoints = array(
                'points' => (int)$customer->points - (int)$points,

            );
            Customer::where('id', $input['customer_id'])->update($nowpoints);

            $medicines = $input['product_name'];
            for ($i = 0; $i < sizeof($medicines); $i++) {
                if ($input['returnqty'][$i] == 0 || $input['returnqty'][$i] == '') {

                    continue;
                } else {

                    $salesreturndetails = array(
                        'sales_return_id' => $return->id,
                        'medicine_id'     => $input['product_id'][$i],
                        'medicine_name'    => $input['product_name'][$i],
                        'size' => $input['size'][$i],
                        'create_date' => Carbon::now(),
                        'sold_qty'         =>  $input['quantity'][$i],
                        'return_qty'       =>  $input['returnqty'][$i],
                        'rate'             => $input['box_mrp'][$i],
                        'total_price'      => $input['total_price'][$i],


                    );

                    SalesReturnDetails::create($salesreturndetails);

                    $outletstockquantity = OutletStock::where('outlet_id', $input['outlet_id'])->where('medicine_id', $input['product_id'][$i])->where('expiry_date', $input['expiry_date'][$i])->first();
                    $newquantity = array(

                        'quantity' => (int)$outletstockquantity->quantity +  $input['returnqty'][$i],

                    );
                    OutletStock::where('outlet_id', $input['outlet_id'])->where('medicine_id', $input['product_id'][$i])->where('expiry_date', $input['expiry_date'][$i])->update($newquantity);
                }
            }

            return redirect()->route('sale-return.index')->with('success', 'Sales Retrun Sucessfull');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment_methods = PaymentMethod::pluck('method_name', 'id');
        $data = OutletInvoice::find($id);
        $medicinedetails = OutletInvoiceDetails::where('outlet_invoice_id', $data->id)->get();

        return view('admin.Pos.sales_retun', compact('data', 'medicinedetails', 'payment_methods'));
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
    public function destroy($id)

    {
        $SalesReturn = SalesReturn::findOrFail($id);

        SalesReturnDetails::where('sales_return_id', $SalesReturn->id)->delete();
        $SalesReturn->delete();
        return redirect()->route('sale-return.index')->with('success', 'Sales Retrun Sucessfully Delete');
    }
    public function details($id)
    {
        $salesReturn = SalesReturn::where('id', $id)->first();
        $salesreturndetails = SalesReturnDetails::where('sales_return_id', $salesReturn->id)->get();
        return view('admin.Pos.sales_return_details', compact('salesreturndetails', 'salesReturn'));
    }

    public function sales_details($id)
    {
        $salesReturn = OutletInvoice::where('id', $id)->first();
        $salesreturndetails = OutletInvoiceDetails::where('outlet_invoice_id', $salesReturn->id)->get();
        $saledetails = OutletPayment::where('invoice_id', $id)->get();

        return view('admin.Pos.sale_details', compact('salesreturndetails', 'salesReturn', 'saledetails'));
    }
}
