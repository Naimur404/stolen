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


    public  function  index(Request $request){

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
        }else{
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
        $invoiceCheck = OutletInvoice::where('id', $invoiceId)->where('outlet_id',$outlet_id)->first();
        if ( $invoiceCheck == null){
            return response()->json(['error' => 'Exchange is Not Possible, in this store'], 404);
        }else{

            if ($invoiceCheck->is_exchange ==1){
                return response()->json(['error' => 'Already one time exchange, So no more Exchange Possible'], 404);
            }else{
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
        $request->validate([
            'exchangeData.invoice_id' => 'required|numeric',
            'exchangeData.exchange_products' => 'required|array',
            'exchangeData.exchange_products.*.id' => 'required|numeric',
            'exchangeData.exchange_products.*.name' => 'required|string',
            'exchangeData.exchange_products.*.size' => 'required|string',
            'exchangeData.exchange_products.*.qty' => 'required|numeric',
            'exchangeData.new_products' => 'required|array',
            'exchangeData.new_products.*.id' => 'required|numeric',
            'exchangeData.new_products.*.name' => 'required|string',
            'exchangeData.new_products.*.size' => 'required|string',
            'exchangeData.new_products.*.qty' => 'required|numeric',
        ]);


//      try{
          $invoiceId = $request->input('exchangeData.invoice_id');
          $exchangeProducts = $request->input('exchangeData.exchange_products');
          $newProducts = $request->input('exchangeData.new_products');
          $originalInvoice = OutletInvoice::where('id',$invoiceId)->first();
          $data = array(
              "outlet_id" => $originalInvoice->outlet_id,
              "original_invoice_id" => $invoiceId,
              "customer_id" =>  $originalInvoice->customer_id,
              "grand_total" => $newProducts[0]['price'],
              "paid_amount" => $exchangeProducts[0]['price'] < $newProducts[0]['price'] ? $newProducts[0]['price'] - $exchangeProducts[0]['price'] : '0',
              "added_by" => Auth::user()->id

          );
          $outletExchange = OutletExchange::create($data);


          $new = array(
              'outlet_exchange_id' => $outletExchange->id,
              'medicine_id' => $newProducts[0]['id'],
              'medicine_name' => $newProducts[0]['name'],
              'size' => $newProducts[0]['size'],
              'quantity' => $newProducts[0]['qty'],
              'available_qty' => $newProducts[0]['avalqty'] - $newProducts[0]['qty'],
              'rate' => $newProducts[0]['price'],
              'total_price' => $newProducts[0]['total_price'],
              'is_exchange' => 0
          );
          $outletExchangeSub = OutletExchangeDetails::create($new);
          if ($outletExchangeSub->id){
              $updateStock = OutletStock::where('outlet_id', $originalInvoice->outlet_id)->where('medicine_id',$newProducts[0]['id'])->
              where('size', $newProducts[0]['size'])->first();
              $updateStock->update([
                  'quantity' => (int)$updateStock->quantity - (int)$newProducts[0]['qty']
              ]);
          }

          $exchange = array(
              'outlet_exchange_id' => $outletExchange->id,
              'medicine_id' => $exchangeProducts[0]['id'],
              'medicine_name' => $exchangeProducts[0]['name'],
              'size' => $exchangeProducts[0]['size'],
              'quantity' => $exchangeProducts[0]['qty'],
              'available_qty' => $exchangeProducts[0]['avalqty'] + $exchangeProducts[0]['qty'],
              'rate' => $exchangeProducts[0]['price'],
              'total_price' => $exchangeProducts[0]['total_price'],
              'is_exchange' => 1
          );



          $outletExchangeAdd = OutletExchangeDetails::create($exchange);
          if ($outletExchangeAdd->id){
              $updateStock2 = OutletStock::where('outlet_id', $originalInvoice->outlet_id)->where('medicine_id',$exchangeProducts[0]['id'])->
              where('size', $exchangeProducts[0]['size'])->first();
              $updateStock2->update([
                  'quantity' => (int)$updateStock2->quantity + (int)$exchangeProducts[0]['qty']
              ]);
          }
          $originalInvoiceDetails = OutletInvoiceDetails::where('outlet_invoice_id',$originalInvoice->id)->where('medicine_id', $exchangeProducts[0]['id'])->
          where('size', $exchangeProducts[0]['size'])->first();
          $subTotal = round($originalInvoice->sub_total - $exchangeProducts[0]['total_price']) + round($newProducts[0]['total_price']);
          $grandTotal = round($subTotal) - round($originalInvoice->total_discount);
          $paidAmount = round($grandTotal);
          $updateInvoice = $originalInvoice->update([
              'sub_total' => $subTotal,
              'grand_total' => $grandTotal,
              'paid_amount' => $paidAmount,
              'is_exchange' => 1
          ]);
          $originalInvoiceDetails->update([
              'medicine_id' => $newProducts[0]['id'],
              'medicine_name' => $newProducts[0]['name'],
              'size' => $newProducts[0]['size'],
              'quantity' => $newProducts[0]['qty'],
              'available_qty' => $newProducts[0]['avalqty'] - $newProducts[0]['qty'],
              'rate' => $newProducts[0]['price'],
              'total_price' => $newProducts[0]['total_price'],
              'is_exchange' => 1
          ]);

          return response()->json([
              'data' =>$invoiceId
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

    Public function exchangeDetails($id){
        $exchange = OutletExchange::where('id',$id)->first();
        $exchangeDetails = OutletExchangeDetails::where('outlet_exchange_id', $id)->get();

        return view('admin.Pos.exchange_details', compact('exchange','exchangeDetails'));
    }

}
