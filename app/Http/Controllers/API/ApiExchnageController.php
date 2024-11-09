<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OutletExchange;
use App\Models\OutletExchangeDetails;
use App\Models\OutletInvoice;
use App\Models\OutletInvoiceDetails;
use App\Models\OutletStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiExchnageController extends Controller
{
    public function apiExchange(Request $request)
    {
        // Get the data first
        $exchangeProducts = collect($request->input('exchange_products', []))
            ->filter(fn($item) => !isset($item['grandTotal']))
            ->values()
            ->all();

        $newProducts = collect($request->input('new_products', []))
            ->filter(fn($item) => !isset($item['grandTotal']))
            ->values()
            ->all();

        // Validate filtered data
        $validator = Validator::make([
            'invoice_id' => $request->input('invoice_id'),
            'exchange_products' => $exchangeProducts,
            'new_products' => $newProducts
        ], [
            'invoice_id' => 'required|numeric',
            'exchange_products' => 'required|array',
            'exchange_products.*.id' => 'required|numeric',
            'exchange_products.*.name' => 'required|string',
            'exchange_products.*.size' => 'required|string',
            'exchange_products.*.qty' => 'required|numeric',
            'exchange_products.*.price' => 'required|numeric',
            'exchange_products.*.total_price' => 'required|numeric',
            'new_products' => 'required|array',
            'new_products.*.id' => 'required|numeric',
            'new_products.*.name' => 'required|string',
            'new_products.*.size' => 'required|string',
            'new_products.*.qty' => 'required|numeric',
            'new_products.*.price' => 'required|numeric',
            'new_products.*.total_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Get the original products and grand totals from request
            $originalExchangeProducts = $request->input('exchange_products');
            $originalNewProducts = $request->input('new_products');

            $exchangeGrandTotal = collect($originalExchangeProducts)
                ->firstWhere('grandTotal')['grandTotal'] ?? 0;

            $newGrandTotal = collect($originalNewProducts)
                ->firstWhere('grandTotal')['grandTotal'] ?? 0;

            $invoiceId = $request->input('invoice_id');
            $originalInvoice = OutletInvoice::findOrFail($invoiceId);

            // Create exchange record
            $outletExchange = OutletExchange::create([
                "outlet_id" => $originalInvoice->outlet_id,
                "original_invoice_id" => $invoiceId,
                "customer_id" => $originalInvoice->customer_id,
                "grand_total" => $exchangeGrandTotal,
                "paid_amount" => $exchangeGrandTotal < $newGrandTotal ? $newGrandTotal - $exchangeGrandTotal : 0,
                "added_by" => Auth::id()
            ]);

            // Process exchange products (use filtered products for processing)
            foreach ($exchangeProducts as $product) {
                // Update stock for returned items
                $updateStock = OutletStock::where('outlet_id', $originalInvoice->outlet_id)
                    ->where('medicine_id', $product['id'])
                    ->where('size', $product['size'])
                    ->firstOrFail();

                $updateStock->increment('quantity', $product['qty']);

                // Create exchange details
                OutletExchangeDetails::create([
                    'outlet_exchange_id' => $outletExchange->id,
                    'medicine_id' => $product['id'],
                    'medicine_name' => $product['name'],
                    'size' => $product['size'],
                    'quantity' => $product['qty'],
                    'available_qty' => $product['avalqty'] + $product['qty'],
                    'rate' => $product['price'],
                    'total_price' => $product['total_price'],
                    'is_exchange' => 1
                ]);

                // Update or delete original invoice details
                $originalInvoiceDetails = OutletInvoiceDetails::where('outlet_invoice_id', $originalInvoice->id)
                    ->where('medicine_id', $product['id'])
                    ->where('size', $product['size'])
                    ->first();

                if ($originalInvoiceDetails) {
                    if (isset($product['purchase']) && $product['purchase'] == $product['qty']) {
                        $originalInvoiceDetails->delete();
                    } else {
                        $originalInvoiceDetails->update([
                            'quantity' => $originalInvoiceDetails->quantity - $product['qty'],
                            'available_qty' => $product['avalqty'] + $product['qty'],
                            'rate' => $product['price'],
                            'total_price' => $product['total_price'],
                            'is_exchange' => 1
                        ]);
                    }
                }
            }

            // Process new products (use filtered products for processing)
            foreach ($newProducts as $product) {
                // Update stock for new items
                $updateStock = OutletStock::where('outlet_id', $originalInvoice->outlet_id)
                    ->where('medicine_id', $product['id'])
                    ->where('size', $product['size'])
                    ->firstOrFail();

                $updateStock->decrement('quantity', $product['qty']);

                // Create exchange details for new products
                OutletExchangeDetails::create([
                    'outlet_exchange_id' => $outletExchange->id,
                    'medicine_id' => $product['id'],
                    'medicine_name' => $product['name'],
                    'size' => $product['size'],
                    'quantity' => $product['qty'],
                    'available_qty' => $product['avalqty'] - $product['qty'],
                    'rate' => $product['price'],
                    'total_price' => $product['total_price'],
                    'is_exchange' => 0
                ]);

                // Update or create invoice details
                $originalInvoiceDetails = OutletInvoiceDetails::where('outlet_invoice_id', $originalInvoice->id)
                    ->where('medicine_id', $product['id'])
                    ->where('size', $product['size'])
                    ->first();

                if ($originalInvoiceDetails) {
                    $originalInvoiceDetails->update([
                        'quantity' => $originalInvoiceDetails->quantity + $product['qty'],
                        'available_qty' => $product['avalqty'] - $product['qty'],
                        'rate' => $product['price'],
                        'total_price' => $product['total_price'],
                        'is_exchange' => 1
                    ]);
                } else {
                    OutletInvoiceDetails::create([
                        'outlet_invoice_id' => $originalInvoice->id,
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
                    ]);
                }
            }

            // Update original invoice
            $subTotal = round($originalInvoice->sub_total - $exchangeGrandTotal + $newGrandTotal);
            $grandTotal = $subTotal - round($originalInvoice->total_discount);

            $originalInvoice->update([
                'sub_total' => $subTotal,
                'grand_total' => $grandTotal,
                'paid_amount' => $grandTotal,
                'is_exchange' => 1
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $invoiceId
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
