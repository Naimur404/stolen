<?php
/**
 * Created by Ariful Islam.
 * Organization : Pigeon Soft
 * Date: 7/4/23
 * Time: 3:13 AM
 */

namespace App\Helpers;

use App\Models\MedicinePurchase;
use App\Models\MedicinePurchaseDetails;
use App\Models\Outlet;
use App\Models\OutletInvoice;
use App\Models\OutletStock;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class SummaryHelper
{
    /**
     * @return array
     */
    public function getWarehouseSummary($summary_date = null): array
    {
        if ($summary_date == null) {
            $summary_date = "M";
        }
        // total records count
        $warehouses = Warehouse::all();
        $total = 0;
        $data_arr = array();
        foreach ($warehouses as $warehouse) {
            $name = Warehouse::where('id', $warehouse->id)->first();
            $purchase_qty = MedicinePurchaseDetails::where('warehouse_id', $warehouse->id)->whereDate('created_at', $summary_date)->sum('quantity');
            $purchase_amt = MedicinePurchase::where('warehouse_id', $warehouse->id)->whereDate('created_at', $summary_date)->sum('grand_total');
            $distribute = DB::table('medicine_distributes')->where('warehouse_id', $warehouse->id)->where('medicine_distributes.has_sent', 1)->where('medicine_distributes.has_received', 1)->whereDate('medicine_distributes.date', $summary_date)
                ->leftJoin('medicine_distribute_details', 'medicine_distributes.id', '=', 'medicine_distribute_details.medicine_distribute_id')->sum('medicine_distribute_details.quantity');
            $request = DB::table('stock_requests')->where('warehouse_id', $warehouse->id)->where('stock_requests.has_sent', 1)->whereDate('stock_requests.date', $summary_date)
                ->leftJoin('stock_request_details', 'stock_requests.id', '=', 'stock_request_details.stock_request_id')->sum('stock_request_details.quantity');
            $stock = WarehouseStock::where('warehouse_id', $warehouse->id)->where('size', '=', $summary_date)->sum('quantity');
            $data_arr[] = array(
                "name" => $name->warehouse_name,
                "purchase_qty" => $purchase_qty,
                "purchase_amt" => $purchase_amt,
                "distribute" => $distribute,
                "request" => $request,
                "stock" => $stock,
            );
        }

        return $data_arr;
    }

    /**
     * @return array
     */
    public function getOutletSummary($summary_date = null): array
    {
        if ($summary_date == null) {
            $summary_date = Carbon::now()->format('Y-m-d');
        }
        $outlets = Outlet::all();

        $total = 0;
        $data_arr = array();
        foreach ($outlets as $outlet) {
            $name = Outlet::where('id', $outlet->id)->first();
            $sale = OutletInvoice::where('outlet_id', $outlet->id)->whereDate('sale_date', $summary_date)->sum('grand_total');
            $due = OutletInvoice::where('outlet_id', $outlet->id)->whereDate('sale_date', $summary_date)->sum('due_amount');

            $return = DB::table('warehouse_returns')->where('outlet_id', $outlet->id)->whereDate('warehouse_returns.date', $summary_date)
                ->leftJoin('warehouse_return_details', 'warehouse_returns.id', '=', 'warehouse_return_details.warehouse_return_id')->sum('warehouse_return_details.quantity');

            $received = DB::table('medicine_distributes')->where('outlet_id', $outlet->id)->where('medicine_distributes.has_sent', 1)->where('medicine_distributes.has_received', 1)->whereDate('medicine_distributes.date', $summary_date)
                ->leftJoin('medicine_distribute_details', 'medicine_distributes.id', '=', 'medicine_distribute_details.medicine_distribute_id')->sum('medicine_distribute_details.quantity');

            $stock = OutletStock::where('outlet_id', $outlet->id)->whereDate('expiry_date', '=', $summary_date)->sum('quantity');
            $data_arr[] = array(
                "name" => $name->outlet_name,
                "sale" => $sale,
                "due" => $due,
                "return" => $return,
                "received" => $received,
                "stock" => $stock,

            );
        }

        return $data_arr;
    }


    public static function sendSMS($number, $text)
    {


        $url = "http://services.smsnet24.com/sendSms";

        $payload = [
            'sms_receiver' =>  $number,
            'sms_text' => $text,
            'campaignType' => 'T',
            'user_password' => 'stolen.com.bd2@',
            'user_id' => 'farsemac@gmail.com'
        ];

        $client = new Client();



            $client->post($url, [
                'form_params' => $payload
            ]);


    }

}
