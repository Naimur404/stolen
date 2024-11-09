<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Outlet;
use App\Models\OutletInvoice;
use App\Models\OutletInvoiceDetails;
use App\Models\OutletStock;
use App\Models\RedeemPointLog;
use App\Jobs\SendSMSJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ApiInvoiceController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate the incoming request data
            $this->validateRequest($request);

            // Get the input data
            $input = $request->all();

            // Get customer based on customer_id
            $customer = Customer::findOrFail($input['customer_id']);

            // Calculate total discount
            $totalDiscount = $this->calculateDiscount($input);

            // Calculate grand total (sub_total - total_discount)
            $grandTotal = $input['sub_total'] - $totalDiscount;

            // Create the invoice
            $outletInvoice = $this->createInvoice($input, $customer, $totalDiscount, $grandTotal);

            // Process invoice details and update stock
            $this->processInvoiceDetails($input, $outletInvoice);

            // Log redeem points if applicable
            if ($input['redeemed_points'] > 0) {
                $this->logRedeemPoints($input, $customer, $outletInvoice);
            }

            // Update customer points
            $this->updateCustomerPoints($customer, $input, $outletInvoice);

            // Send SMS notifications
            $this->sendInvoiceSMS($customer, $outletInvoice, $input);
            // $text = "Hey  thanks for shopping at Stolen! Your order is on its way! Tracking";


            // SendSMSJob::dispatch('880' . substr($customer->mobile, 1), $text);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Invoice created successfully',
                'data' => $outletInvoice->load('invoiceDetails', 'customer', 'outlet')
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    private function validateRequest($request)
    {
        return $request->validate([
            'outlet_id' => 'required|integer|exists:outlets,id',
            'customer_id' => 'required|integer|exists:customers,id',
            'payment_type' => 'required|integer',
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer|exists:medicines,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.stock_id' => 'required|integer|exists:outlet_stocks,id',
            'products.*.size' => 'required|string',
            'products.*.discount' => 'required|numeric|min:0',
            'given_amount' => 'required|numeric|min:0',
            'payable_amount' => 'required|numeric|min:0',
            'sub_total' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'flat_discount' => 'required|numeric|min:0',
            'change_amount' => 'required|numeric|min:0',
            'total_discount' => 'required|numeric|min:0',
            'redeemed_points' => 'required|numeric|min:0'
        ]);
    }

    private function calculateDiscount($input)
    {
        // Calculate percentage discount
        $percentageDiscount = 0;
        if ($input['discount_percentage'] > 0) {
            $percentageDiscount = round(($input['sub_total'] * $input['discount_percentage']) / 100, 2);
        }

        // Add flat discount if any
        $totalDiscount = $percentageDiscount + $input['flat_discount'];

        // Verify that calculated discount matches the provided total_discount
        if (abs($totalDiscount - $input['total_discount']) > 0.01) {
            throw new Exception('Calculated discount does not match provided total discount');
        }

        return $totalDiscount;
    }

    private function createInvoice($input, $customer, $totalDiscount, $grandTotal)
    {
        return OutletInvoice::create([
            'outlet_id' => $input['outlet_id'],
            'customer_id' => $customer->id,
            'sale_date' => Carbon::now(),
            'invoice_number' => $this->generateInvoiceNumber($input['outlet_id']),
            'sub_total' => $input['sub_total'],
            'total_discount' => $totalDiscount,
            'grand_total' => $grandTotal,
            'given_amount' => $input['given_amount'],
            'payable_amount' => $input['payable_amount'],
            'paid_amount' => min($input['given_amount'], $input['payable_amount']),
            'change_amount' => $input['change_amount'],
            'redeem_point' => $input['redeemed_points'],
            'earn_point' => round(($input['sub_total'] / 100), 2),
            'payment_method_id' => $input['payment_type'],
            'added_by' => Auth::user()->id,
        ]);
    }

    private function generateInvoiceNumber($outletId)
    {
        $latestInvoice = OutletInvoice::where('outlet_id', $outletId)
            ->latest()
            ->first();

        $currentNumber = $latestInvoice ? (int)substr($latestInvoice->invoice_number, -6) : 0;
        $nextNumber = $currentNumber + 1;

        return 'INV' . $outletId . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    private function processInvoiceDetails($input, $outletInvoice)
    {
        foreach ($input['products'] as $product) {
            // Verify stock availability
            $stock = OutletStock::where('outlet_id', $input['outlet_id'])
                ->where('medicine_id', $product['product_id'])
                ->where('size', $product['size'])
                ->first();

            if (!$stock || $stock->quantity < $product['quantity']) {
                throw new Exception("Insufficient stock for product ID: {$product['product_id']}");
            }

            // Create invoice detail record
            OutletInvoiceDetails::create([
                'outlet_invoice_id' => $outletInvoice->id,
                'stock_id' => $product['stock_id'],
                'medicine_id' => $product['product_id'],
                'medicine_name' => $product['name'],
                'size' => $product['size'],
                'quantity' => $product['quantity'],
                'rate' => $product['price'],
                'discount' => $product['discount'],
                'total_price' => $product['price'] * $product['quantity'],
            ]);

            // Update stock quantity
            $this->updateStockQuantity(
                $input['outlet_id'],
                $product['product_id'],
                $product['size'],
                $product['quantity']
            );
        }
    }

    private function updateStockQuantity($outletId, $productId, $size, $quantity)
    {
        OutletStock::where('outlet_id', $outletId)
            ->where('medicine_id', $productId)
            ->where('size', $size)
            ->decrement('quantity', $quantity);
    }

    private function logRedeemPoints($input, $customer, $outletInvoice)
    {
        RedeemPointLog::create([
            'outlet_id' => $input['outlet_id'],
            'customer_id' => $customer->id,
            'invoice_id' => $outletInvoice->id,
            'previous_point' => $customer->points,
            'redeem_point' => $input['redeemed_points'],
             // Active status
        ]);
    }

    private function updateCustomerPoints($customer, $input, $outletInvoice)
    {
        $newPoints = $customer->points - $input['redeemed_points'] + $outletInvoice->earn_point;

        $customer->update([
            'points' => max(0, $newPoints),
            'last_purchase_date' => Carbon::now(),
            'total_purchase_amount' => $customer->total_purchase_amount + $input['payable_amount']
        ]);
    }

    private function sendInvoiceSMS($customer, $outletInvoice, $input)
    {
        // // Get outlet details
        $outlet = Outlet::find($input['outlet_id']);

        // Format mobile number (remove leading zero and add country code)
        $mobileNumber = '880' . substr($customer->mobile, 1);

        // Different SMS templates based on scenarios


            $this->sendRegularPurchaseSMS($mobileNumber, $customer, $outletInvoice, $outlet);


        // Send special promotions or welcome message for first-time customers
        if ($this->isFirstPurchase($customer)) {
            $this->sendWelcomeSMS($mobileNumber, $customer, $outlet);
        }
    }

    private function sendRedeemPointsSMS($mobile, $customer, $outletInvoice, $outlet)
    {
        $message = sprintf(
            "Dear %s, you've redeemed %s points on your purchase of BDT %s at %s. Your current point balance is %s. Thank you for shopping with us!",
            $customer->name,
            $outletInvoice->redeem_point,
            number_format($outletInvoice->payable_amount, 2),
            $outlet->outlet_name,
            $customer->points
        );

        SendSMSJob::dispatch($mobile, $message);
    }

    private function sendEarnPointsSMS($mobile, $customer, $outletInvoice, $outlet)
    {
        $message = sprintf(
            "Dear %s, you've earned %s points on your purchase of BDT %s at %s. Your total point balance is %s. Shop more to earn more rewards!",
            $customer->name,
            $outletInvoice->earn_point,
            number_format($outletInvoice->payable_amount, 2),
            $outlet->outlet_name,
            $customer->points
        );

        SendSMSJob::dispatch($mobile, $message);
    }

    private function sendRegularPurchaseSMS($mobile, $customer, $outletInvoice, $outlet)
    {
        $message = "Hey  thanks for shopping at Stolen!";


        SendSMSJob::dispatch($mobile, $message);
    }

    private function sendWelcomeSMS($mobile, $customer, $outlet)
    {
        $message = sprintf(
            "Welcome to %s! Dear %s, thank you for your first purchase. Earn points on every purchase and enjoy exclusive benefits. Visit: https://stolen.com.bd",
            $outlet->outlet_name,
            $customer->name
        );

        SendSMSJob::dispatch($mobile, $message);
    }

    private function isFirstPurchase($customer)
    {
        return OutletInvoice::where('customer_id', $customer->id)
            ->count() === 1;
    }

    // SMS for special occasions like birthdays or anniversaries
    private function sendBirthdayPromotionalSMS($mobile, $customer, $outlet)
    {
        if ($customer->birth_date && $customer->birth_date->isToday()) {
            $message = sprintf(
                "Happy Birthday %s! 🎂 Enjoy special 10%% discount on your purchase today at %s. Valid until midnight. T&C apply.",
                $customer->name,
                $outlet->outlet_name
            );

            SendSMSJob::dispatch($mobile, $message);
        }
    }

    // SMS for order tracking (if applicable)
    private function sendOrderTrackingSMS($mobile, $customer, $trackingUrl)
    {
        $message = sprintf(
            "Hey %s, thanks for shopping with us! Track your order here: %s",
            $customer->name,
            $trackingUrl
        );

        SendSMSJob::dispatch($mobile, $message);
    }

    // Helper method to format phone numbers
    private function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove leading zero if exists
        if (substr($phone, 0, 1) === '0') {
            $phone = substr($phone, 1);
        }

        // Add country code if not present
        if (substr($phone, 0, 3) !== '880') {
            $phone = '880' . $phone;
        }

        return $phone;
    }
}
