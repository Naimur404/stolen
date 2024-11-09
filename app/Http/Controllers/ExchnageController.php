<?php

namespace App\Http\Controllers;


use App\Models\Outlet;
use App\Models\OutletExchange;
use App\Models\OutletExchangeDetails;
use App\Models\OutletInvoice;
use App\Models\OutletInvoiceDetails;
use App\Models\OutletStock;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


class ExchnageController extends Controller
{
    public function __construct()
    {
        //        $this->middleware('permission:customer.management|customer.create|customer.edit|customer.delete', ['only' => ['customer', 'store']]);
        //        $this->middleware('permission:customer.create', ['only' => ['create', 'store']]);
        //        $this->middleware('permission:customer.edit', ['only' => ['edit', 'update']]);
        //        $this->middleware('permission:customer.delete', ['only' => ['destroy']]);
        //        $this->middleware('permission:customer-due', ['only' => ['customerDue']]);
        //        $this->middleware('permission:customer-due-payment', ['only' => ['customerDuePayment']]);
    }


    public  function  index(Request $request)
    {

        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
        if (auth()->user()->hasrole('Super Admin')) {
            if ($request->ajax()) {
                $data = OutletExchange::with(['customer'])->orderBy("id", "desc")->get();
                return  DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $id = $row->id;

                        $details = '<a class="btn btn-primary" href="' . route('exchange.details', ['id' => $id]) . '">Show</a>';


                        return $details;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } else {
            if ($request->ajax()) {
                $data = OutletExchange::with(['customer'])->where('outlet_id', $outlet_id)->orderBy("id", "desc")->get();
                return  DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $id = $row->id;

                        $details = '<a class="btn btn-primary" href="' . route('exchange.details', ['id' => $id]) . '">Show</a>';


                        return $details;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }

        return view('admin.Pos.exchange_index');
    }

    public function create()
    {
        return view('admin.Pos.exchange');
    }

    public function getProducts($invoiceId)
    {
        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
        $invoiceCheck = OutletInvoice::where('id', $invoiceId)->where('outlet_id', $outlet_id)->first();
        if ($invoiceCheck == null) {
            return response()->json(['error' => 'Exchange is Not Possible, in this store'], 404);
        } else {

            if ($invoiceCheck->is_exchange == 1) {
                return response()->json(['error' => 'Already one time exchange, So no more Exchange Possible'], 404);
            } else {
                $invoiceDate = Carbon::parse($invoiceCheck->created_at);

                // Get the current date and time
                $currentDate = Carbon::now();

                // Calculate the difference in days
                $daysDifference = $currentDate->diffInDays($invoiceDate);
                if ($daysDifference > 4) {
                    // Invoice is greater than 4 days old
                    // Your logic here
                    return response()->json(['error' => 'Exchange Not Possible, Purchase Date More then 4 days'], 404);
                } else {
                    // Invoice is not greater than 4 days old
                    // Your logic here
                    $products = OutletInvoiceDetails::where('outlet_invoice_id', $invoiceId)->get();
                    return response()->json($products);
                }
            }
        }



        // Check if the invoice is greater than 4 days old


    }


    public function exchange(Request $request)

    {
        dump($request->all());
        // $request->validate([
        //     'exchangeData.invoice_id' => 'required|numeric',
        //     'exchangeData.exchange_products' => 'required|array',
        //     'exchangeData.exchange_products.*.id' => 'required|numeric',
        //     'exchangeData.exchange_products.*.name' => 'required|string',
        //     'exchangeData.exchange_products.*.size' => 'required|string',
        //     'exchangeData.exchange_products.*.qty' => 'required|numeric',
        //     'exchangeData.new_products' => 'required|array',
        //     'exchangeData.new_products.*.id' => 'required|numeric',
        //     'exchangeData.new_products.*.name' => 'required|string',
        //     'exchangeData.new_products.*.size' => 'required|string',
        //     'exchangeData.new_products.*.qty' => 'required|numeric',
        // ]);


        //      try{
        $invoiceId = $request->input('exchangeData.invoice_id');
        $exchangeProducts = $request->input('exchangeData.exchange_products');
        $newProducts = $request->input('exchangeData.new_products');
        $originalInvoice = OutletInvoice::where('id', $invoiceId)->first();

        $exchangeGrandTotal = null;

if (is_array($exchangeProducts)) {
    foreach ($exchangeProducts as $product) {
        if (is_array($product) && array_key_exists('grandTotal', $product)) {
            $exchangeGrandTotal = $product['grandTotal'];
            break;
        }
    }
}
$newGrandTotal = null;

if (is_array($newProducts)) {
    foreach ($newProducts as $product) {
        if (is_array($product) && array_key_exists('grandTotal', $product)) {
            $newGrandTotal = $product['grandTotal'];
            break;
        }
    }
}
        // $newGrandTotal = collect($newProducts)->pluck('grandTotal')->first();
        $data = array(
            "outlet_id" => $originalInvoice->outlet_id,
            "original_invoice_id" => $invoiceId,
            "customer_id" =>  $originalInvoice->customer_id,
            "grand_total" => $exchangeGrandTotal,
            "paid_amount" => $exchangeGrandTotal < $newGrandTotal ? $newGrandTotal - $exchangeGrandTotal : '0',
            "added_by" => Auth::user()->id

        );
        $outletExchange = OutletExchange::create($data);


        foreach ($exchangeProducts as $product) {
            if (isset($product['grandTotal'])) {

            break;
        }else{
            $exchange = array(
                'outlet_exchange_id' => $outletExchange->id,
                'medicine_id' => $product['id'],
                'medicine_name' => $product['name'],
                'size' => $product['size'],
                'quantity' => $product['qty'],
                'available_qty' => $product['avalqty'] + $product['qty'],
                'rate' => $product['price'],
                'total_price' => $product['total_price'],
                'is_exchange' => 1
            );

            $outletExchangeAdd = OutletExchangeDetails::create($exchange);
            if ($outletExchangeAdd->id) {
                $updateStock2 = OutletStock::where('outlet_id', $originalInvoice->outlet_id)->where('medicine_id', $product['id'])->where('size', $product['size'])->first();
                $updateStock2->update([
                    'quantity' => (int)$updateStock2->quantity + (int)$product['qty']
                ]);
            }

            $originalInvoiceDetails = OutletInvoiceDetails::where('outlet_invoice_id', $originalInvoice->id)->where('medicine_id', $product['id'])->where('size', $product['size'])->first();

            if($originalInvoiceDetails != null){
                if($product['purchase'] == $product['qty']){
                    $originalInvoiceDetails->delete();
                }

                 if($product['purchase'] < $product['qty']){
                    $originalInvoiceDetails->update([
                        'quantity' => (int)$originalInvoiceDetails->quantity - (int)$product['qty'],
                        'available_qty' => $product['avalqty'] + $product['qty'],
                        'rate' => $product['price'],
                        'total_price' => $product['total_price'],
                        'is_exchange' => 1
                    ]);
                }

            }
        }
        }


        foreach ($newProducts as $product) {
            if (isset($product['grandTotal'])) {

            break;
        }else{
            $new = array(
                'outlet_exchange_id' => $outletExchange->id,
                'medicine_id' => $product['id'],
                'medicine_name' => $product['name'],
                'size' => $product['size'],
                'quantity' => $product['qty'],
                'available_qty' => $product['avalqty'] - $product['qty'],
                'rate' => $product['price'],
                'total_price' => $product['total_price'],
                'is_exchange' => 0
            );

            $outletExchangeSub = OutletExchangeDetails::create($new);
            if ($outletExchangeSub->id) {
                $updateStock = OutletStock::where('outlet_id', $originalInvoice->outlet_id)->where('medicine_id', $product['id'])->where('size', $product['size'])->first();
                $updateStock->update([
                    'quantity' => (int)$updateStock->quantity - (int)$product['qty']
                ]);
            }


            $originalInvoiceDetails = OutletInvoiceDetails::where('outlet_invoice_id', $originalInvoice->id)->where('medicine_id', $product['id'])->where('size', $product['size'])->first();

            if($originalInvoiceDetails != null){

                    $originalInvoiceDetails->update([
                        'quantity' => (int)$originalInvoiceDetails->quantity + (int)$product['qty'],
                        'available_qty' => $product['avalqty'] - $newProducts['qty'],
                        'rate' => $product['price'],
                        'total_price' => $product['total_price'],
                        'is_exchange' => 1
                    ]);
                }

        else {
                $invoicedetails = array(

                    'outlet_invoice_id' => $originalInvoice ->id,
                    'stock_id' => 1,
                    'medicine_id' => $product['id'],
                    'medicine_name' => $product['name'],
                    'size' => $product['size'],

                    'available_qty' => $product['avalqty'] - $product['qty'],

                    'quantity' => $product['qty'],
                    'rate' => $product['price'],
                    'discount' => 0,
                    'total_price' => round($product['total_price']),
                    'is_exchange' => 1

                );
                OutletInvoiceDetails::create($invoicedetails);

            }
        }
        }

        $subTotal = round($originalInvoice->sub_total - $exchangeGrandTotal) + round($newGrandTotal);
        $grandTotal = round($subTotal) - round($originalInvoice->total_discount);
        $paidAmount = round($grandTotal);
        $updateInvoice = $originalInvoice->update([
            'sub_total' => $subTotal,
            'grand_total' => $grandTotal,
            'paid_amount' => $paidAmount,
            'is_exchange' => 1
        ]);



        return response()->json([
            'data' => $invoiceId
        ]);

        //      } catch (\Exception $e) {
        //return redirect()->back()->with('error', $e->getMessage());
        //}
    }

    public function printInvoice($id)
    {
        $outletInvoice = OutletInvoice::find($id);
        $outletInvoicedetails = OutletInvoiceDetails::where('outlet_invoice_id', $id)->where('is_exchange', 1)->get();
        return view('admin.invoice.print2', compact('outletInvoice', 'outletInvoicedetails'));
    }

    public function exchangeDetails($id)
    {
        $exchange = OutletExchange::where('id', $id)->first();
        $exchangeDetails = OutletExchangeDetails::where('outlet_exchange_id', $id)->get();

        return view('admin.Pos.exchange_details', compact('exchange', 'exchangeDetails'));
    }
}
