<?php

namespace App\Http\Controllers;

use App\Models\MedicinePurchase;
use App\Models\MedicinePurchaseDetails;
use App\Models\Outlet;
use App\Models\OutletInvoice;
use App\Models\OutletStock;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashBoardController extends Controller
{

    function __construct()
    {

        $this->middleware('permission:outlet-summary', ['only' => ['summaryOutlet']]);
        $this->middleware('permission:warehouse-summary', ['only' => ['summaryWarehouse']]);
    }

 public function index()
    {
        $data = 0;
        $card = 0;
        $service = 0;
        $tran = 0;
        $totaldata = 0;
        $totalcard = 0;
        $totalservice = 0;
        $totaltran = 0;
        return view('admin.color-version.index', compact('data', 'card', 'service', 'tran', 'totaldata', 'totalcard', 'totalservice', 'totaltran'));
    }

 public function summaryWarehouse()

    {
        // total records count
        $warehouses  = Warehouse::all();
        $total = 0;
        $data_arr = array();
        foreach ($warehouses as $warehouse) {
            $name = Warehouse::where('id', $warehouse->id)->first();
            $purchase_qty = MedicinePurchaseDetails::where('warehouse_id', $warehouse->id)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->sum('quantity');
            $purchase_amt = MedicinePurchase::where('warehouse_id', $warehouse->id)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->sum('grand_total');
            $distribute = DB::table('medicine_distributes')->where('warehouse_id', $warehouse->id)->where('medicine_distributes.has_sent', 1)->where('medicine_distributes.has_received', 1)->whereDate('medicine_distributes.date', Carbon::now()->format('Y-m-d'))
                ->leftJoin('medicine_distribute_details', 'medicine_distributes.id', '=', 'medicine_distribute_details.medicine_distribute_id')->sum('medicine_distribute_details.quantity');
            $request = DB::table('stock_requests')->where('warehouse_id', $warehouse->id)->where('stock_requests.has_sent', 1)->whereDate('stock_requests.date', Carbon::now()->format('Y-m-d'))
                ->leftJoin('stock_request_details', 'stock_requests.id', '=', 'stock_request_details.stock_request_id')->sum('stock_request_details.quantity');
            $stock = WarehouseStock::where('warehouse_id', $warehouse->id)->whereDate('expiry_date', '=', Carbon::now()->format('Y-m-d'))->sum('quantity');
            $data_arr[] = array(
                "name" => $name->warehouse_name,
                "purchase_qty" => $purchase_qty,
                "purchase_amt" => $purchase_amt,
                "distribute" => $distribute,
                "request"  => $request,
                "stock"    => $stock,
            );
        }

        return view('admin.summary.warehouse', compact('data_arr'));
    }

public function summaryOutlet()

    {

        $outlets  = Outlet::all();

        $total = 0;
        $data_arr = array();
        foreach ($outlets as $outlet) {
            $name = Outlet::where('id', $outlet->id)->first();
            $sale = OutletInvoice::where('outlet_id', $outlet->id)->whereDate('sale_date', Carbon::now()->format('Y-m-d'))->sum('grand_total');
            $due = OutletInvoice::where('outlet_id', $outlet->id)->whereDate('sale_date', Carbon::now()->format('Y-m-d'))->sum('due_amount');

            $return = DB::table('warehouse_returns')->where('outlet_id', $outlet->id)->whereDate('warehouse_returns.date', Carbon::now()->format('Y-m-d'))
                ->leftJoin('warehouse_return_details', 'warehouse_returns.id', '=', 'warehouse_return_details.warehouse_return_id')->sum('warehouse_return_details.quantity');

            $received = DB::table('medicine_distributes')->where('outlet_id', $outlet->id)->where('medicine_distributes.has_sent', 1)->where('medicine_distributes.has_received', 1)->whereDate('medicine_distributes.date', Carbon::now()->format('Y-m-d'))
                ->leftJoin('medicine_distribute_details', 'medicine_distributes.id', '=', 'medicine_distribute_details.medicine_distribute_id')->sum('medicine_distribute_details.quantity');

            $stock = OutletStock::where('outlet_id', $outlet->id)->whereDate('expiry_date', '=', Carbon::now()->format('Y-m-d'))->sum('quantity');
            $data_arr[] = array(
                "name" => $name->outlet_name,
                "sale" => $sale,
                "due" => $due,
                "return" => $return,
                "received" => $received,
                "stock"    => $stock,

            );
        }

        return view('admin.summary.outlet', compact('data_arr'));
    }
}
