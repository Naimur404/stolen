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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'exchangeData.invoice_id' => 'required',
            'exchangeData.exchange_products' => 'required|array',
            'exchangeData.new_products' => 'required|array',
            'exchangeData.balance' => 'numeric',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }
    
        try {
            // Begin database transaction to ensure data integrity
            DB::beginTransaction();
    
            // Extract request data
            $invoiceId = $request->input('exchangeData.invoice_id');
            $exchangeProducts = $request->input('exchangeData.exchange_products');
            $newProducts = $request->input('exchangeData.new_products');
            
            // Find the original invoice
            $originalInvoice = OutletInvoice::findOrFail($invoiceId);
            
            // Extract grand totals from product arrays
            $exchangeGrandTotal = $this->extractGrandTotal($exchangeProducts);
            $newGrandTotal = $this->extractGrandTotal($newProducts);
            
            // Ensure we have valid grand totals
            if ($exchangeGrandTotal === null || $newGrandTotal === null) {
                throw new \Exception('Invalid grand total values in exchange data');
            }
            
            // Calculate the payment amount (if new products cost more than returned products)
            $paidAmount = $newGrandTotal > $exchangeGrandTotal ? $newGrandTotal - $exchangeGrandTotal : 0;
            
            // Create the exchange record
            $outletExchange = OutletExchange::create([
                'outlet_id' => $originalInvoice->outlet_id,
                'original_invoice_id' => $invoiceId,
                'customer_id' => $originalInvoice->customer_id,
                'grand_total' => $exchangeGrandTotal,
                'paid_amount' => $paidAmount,
                'added_by' => Auth::id()
            ]);
            
            // Process returned products
            $this->processReturnedProducts($exchangeProducts, $outletExchange, $originalInvoice);
            
            // Process new products
            $this->processNewProducts($newProducts, $outletExchange, $originalInvoice);
            
            // Update the original invoice totals
            $this->updateOriginalInvoice($originalInvoice, $exchangeGrandTotal, $newGrandTotal);
            
            // Commit the transaction
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => $invoiceId
            ]);
            
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            // Log the error for debugging
            Log::error('Exchange error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing exchange: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Extract the grand total from a products array
     * 
     * @param array $products Array of products
     * @return float|null The grand total or null if not found
     */
    private function extractGrandTotal($products)
    {
        if (!is_array($products)) {
            return null;
        }
        
        foreach ($products as $product) {
            if (is_array($product) && isset($product['grandTotal'])) {
                return floatval($product['grandTotal']);
            }
        }
        
        return null;
    }
    
    /**
     * Process returned products from an exchange
     * 
     * @param array $products Products being returned
     * @param OutletExchange $exchange The exchange record
     * @param OutletInvoice $invoice The original invoice
     */
    private function processReturnedProducts($products, $exchange, $invoice)
    {
        foreach ($products as $product) {
            // Skip the grand total entry
            if (isset($product['grandTotal'])) {
                continue;
            }
            
            // Create exchange detail record for returned product
            $exchangeDetail = OutletExchangeDetails::create([
                'outlet_exchange_id' => $exchange->id,
                'medicine_id' => $product['id'],
                'medicine_name' => $product['name'],
                'size' => $product['size'],
                'quantity' => $product['qty'],
                'available_qty' => $product['avalqty'] + $product['qty'],
                'rate' => $product['price'],
                'total_price' => $product['total_price'],
                'is_exchange' => 1 // 1 for returned products
            ]);
            
            // Update outlet stock (add back the returned quantity)
            $stock = OutletStock::where('outlet_id', $invoice->outlet_id)
                ->where('medicine_id', $product['id'])
                ->where('size', $product['size'])
                ->first();
                
            if ($stock) {
                $stock->update([
                    'quantity' => (int)$stock->quantity + (int)$product['qty']
                ]);
            }
            
            // Update or delete the original invoice details
            $originalInvoiceDetail = OutletInvoiceDetails::where('outlet_invoice_id', $invoice->id)
                ->where('medicine_id', $product['id'])
                ->where('size', $product['size'])
                ->first();
            
            if ($originalInvoiceDetail) {
                $purchaseQty = isset($product['purchase']) && !is_nan($product['purchase']) 
                    ? (int)$product['purchase'] 
                    : 0;
                    
                // If all purchased quantity is returned, delete the record
                if ($purchaseQty == (int)$product['qty']) {
                    $originalInvoiceDetail->delete();
                } 
                // Otherwise update the quantity
                else if ($purchaseQty < (int)$product['qty']) {
                    $originalInvoiceDetail->update([
                        'quantity' => (int)$originalInvoiceDetail->quantity - (int)$product['qty'],
                        'available_qty' => $product['avalqty'] + (int)$product['qty'],
                        'rate' => $product['price'],
                        'total_price' => $product['total_price'],
                        'is_exchange' => 1
                    ]);
                }
            }
        }
    }
    
    /**
     * Process new products for an exchange
     * 
     * @param array $products New products being added
     * @param OutletExchange $exchange The exchange record
     * @param OutletInvoice $invoice The original invoice
     */
    private function processNewProducts($products, $exchange, $invoice)
    {
        foreach ($products as $product) {
            // Skip the grand total entry
            if (isset($product['grandTotal'])) {
                continue;
            }
            
            // Create exchange detail record for new product
            $exchangeDetail = OutletExchangeDetails::create([
                'outlet_exchange_id' => $exchange->id,
                'medicine_id' => $product['id'],
                'medicine_name' => $product['name'],
                'size' => $product['size'],
                'quantity' => $product['qty'],
                'available_qty' => $product['avalqty'] - $product['qty'],
                'rate' => $product['price'],
                'total_price' => $product['total_price'],
                'is_exchange' => 0 // 0 for new products
            ]);
            
            // Update outlet stock (decrease the quantity)
            $stock = OutletStock::where('outlet_id', $invoice->outlet_id)
                ->where('medicine_id', $product['id'])
                ->where('size', $product['size'])
                ->first();
                
            if ($stock) {
                $stock->update([
                    'quantity' => (int)$stock->quantity - (int)$product['qty']
                ]);
            }
            
            // Update or create the invoice details for this product
            $originalInvoiceDetail = OutletInvoiceDetails::where('outlet_invoice_id', $invoice->id)
                ->where('medicine_id', $product['id'])
                ->where('size', $product['size'])
                ->first();
            
            if ($originalInvoiceDetail) {
                // Update existing product in the invoice
                $originalInvoiceDetail->update([
                    'quantity' => (int)$originalInvoiceDetail->quantity + (int)$product['qty'],
                    'available_qty' => $product['avalqty'] - (int)$product['qty'],
                    'rate' => $product['price'],
                    'total_price' => $product['total_price'],
                    'is_exchange' => 1
                ]);
            } else {
                // Add new product to the invoice
                OutletInvoiceDetails::create([
                    'outlet_invoice_id' => $invoice->id,
                    'stock_id' => 1,
                    'medicine_id' => $product['id'],
                    'medicine_name' => $product['name'],
                    'size' => $product['size'],
                    'available_qty' => $product['avalqty'] - (int)$product['qty'],
                    'quantity' => $product['qty'],
                    'rate' => $product['price'],
                    'discount' => 0,
                    'total_price' => round($product['total_price']),
                    'is_exchange' => 1
                ]);
            }
        }
    }
    
    /**
     * Update the original invoice with new totals
     * 
     * @param OutletInvoice $invoice The invoice to update
     * @param float $exchangeTotal Total value of returned products
     * @param float $newTotal Total value of new products
     */
    private function updateOriginalInvoice($invoice, $exchangeTotal, $newTotal)
    {
        // Calculate new totals
        $subTotal = round($invoice->sub_total - $exchangeTotal) + round($newTotal);
        $grandTotal = round($subTotal) - round($invoice->total_discount);
        
        // Update the invoice
        $invoice->update([
            'sub_total' => $subTotal,
            'grand_total' => $grandTotal,
            'paid_amount' => $grandTotal,
            'is_exchange' => 1
        ]);
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
