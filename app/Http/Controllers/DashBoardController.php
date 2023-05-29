<?php

namespace App\Http\Controllers;

use App\Helpers\SummaryHelper;
use App\Models\Customer;
use App\Models\MedicinePurchase;
use App\Models\Outlet;
use App\Models\OutletInvoice;
use App\Models\OutletStock;
use App\Models\SalesReturn;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashBoardController extends Controller
{

    protected $summaryHelper;

    public function __construct()
    {
        $this->middleware('permission:outlet-summary', ['only' => ['summaryOutlet']]);
        $this->middleware('permission:warehouse-summary', ['only' => ['summaryWarehouse']]);
        $this->summaryHelper = new SummaryHelper();
    }

    public function index()
    {

        if (auth()->user()->hasrole(['Super Admin', 'Admin'])) {
            $customers = Customer::count();
            $products = OutletStock::where('quantity', '>', 0)->count();
            $stocks = OutletStock::where('quantity', '<', 10)->count();
            $purchases = MedicinePurchase::whereDate('purchase_date', Carbon::now())->sum('grand_total');
            $sales = OutletInvoice::whereDate('sale_date', Carbon::now())->sum('payable_amount');
            $returns = SalesReturn::whereDate('return_date', Carbon::now())->count();
            $invoices = OutletInvoice::whereDate('sale_date', Carbon::now())->count();
            $lastdaysales = OutletInvoice::whereDate('sale_date', today()->subDay())->sum('payable_amount');
            $thisMonthPurchases = MedicinePurchase::whereBetween('purchase_date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->sum('grand_total');
            $thisMonthSales = OutletInvoice::whereBetween('sale_date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->sum('grand_total');
            $thisMonthReturns = SalesReturn::whereBetween('return_date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->count();
            $thisMonthInvoices = OutletInvoice::whereBetween('sale_date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->count();

        } else {
            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
            $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');
            $customers = Customer::where('outlet_id', $outlet_id)->count();
            $purchases = MedicinePurchase::where('warehouse_id', $warehouse_id)->whereDate('purchase_date', Carbon::now())->sum('grand_total');
            $sales = OutletInvoice::where('outlet_id', $outlet_id)->whereDate('sale_date', Carbon::now())->sum('grand_total');
            $returns = SalesReturn::where('outlet_id', $outlet_id)->whereDate('return_date', Carbon::now())->count();
            $products = OutletStock::where('outlet_id', $outlet_id)->where('quantity', '>', 0)->count();
            $stocks = OutletStock::where('outlet_id', $outlet_id)->where('quantity', '<', 10)->count();
            $invoices = OutletInvoice::where('outlet_id', $outlet_id)->whereDate('sale_date', Carbon::now())->count();
            $lastdaysales = OutletInvoice::where('outlet_id', $outlet_id)->whereDate('sale_date', today()->subDay())->sum('payable_amount');

            $thisMonthPurchases = MedicinePurchase::where('warehouse_id', $warehouse_id)->whereBetween('purchase_date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->sum('grand_total');

            $thisMonthSales = OutletInvoice::where('outlet_id', $outlet_id)->whereBetween('sale_date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->sum('grand_total');
            $thisMonthReturns = SalesReturn::where('outlet_id', $outlet_id)->whereBetween('return_date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->count();
            $thisMonthInvoices = OutletInvoice::where('outlet_id', $outlet_id)->whereBetween('sale_date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->count();
        }

        return view('admin.color-version.index', compact('customers', 'products', 'stocks', 'purchases', 'sales', 'returns', 'invoices','thisMonthPurchases','thisMonthSales','thisMonthReturns','thisMonthInvoices','lastdaysales'));
    }

    public function totalSale(Request $request)
    {
        if (auth()->user()->hasrole(['Super Admin', 'Admin'])) {

            $topSalesProducts = DB::table('outlet_invoice_details')->
                whereBetween('outlet_invoice_details.created_at',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])
                ->leftJoin('medicines', 'medicines.id', '=', 'outlet_invoice_details.medicine_id')
                ->select('medicines.id', 'medicines.medicine_name', 'outlet_invoice_details.medicine_id',
                    DB::raw('SUM(outlet_invoice_details.quantity) as total'), DB::raw('COUNT(outlet_invoice_details.medicine_id) as count'))
                ->groupBy('medicines.id', 'medicines.medicine_name', 'outlet_invoice_details.medicine_id')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();
        } else {
            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
            $get_invoice = OutletInvoice::whereBetween('sale_date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->where('outlet_id', $outlet_id)->pluck('id');
            $topSalesProducts = DB::table('outlet_invoice_details')->
                whereBetween('outlet_invoice_details.created_at',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->whereIn('outlet_invoice_details.outlet_invoice_id', $get_invoice)
                ->leftJoin('medicines', 'medicines.id', '=', 'outlet_invoice_details.medicine_id')
                ->select('medicines.id', 'medicines.medicine_name', 'outlet_invoice_details.medicine_id',
                    DB::raw('SUM(outlet_invoice_details.quantity) as total'), DB::raw('COUNT(outlet_invoice_details.medicine_id) as count'))
                ->groupBy('medicines.id', 'medicines.medicine_name', 'outlet_invoice_details.medicine_id')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();

        }

        return response()->json($topSalesProducts);

    }

    public function totalPurchase(Request $request)
    {
        if (auth()->user()->hasrole(['Super Admin', 'Admin'])) {

            $topPurchaseProducts = DB::table('medicine_purchase_details')
                ->whereBetween('medicine_purchase_details.created_at',
                    [
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth(),
                    ])
                ->leftJoin('medicines', 'medicines.id', '=', 'medicine_purchase_details.medicine_id')
                ->select('medicines.id', 'medicines.medicine_name', 'medicine_purchase_details.medicine_id',
                    DB::raw('SUM(medicine_purchase_details.quantity) as total'), DB::raw('COUNT(medicine_purchase_details.medicine_id) as count'))
                ->groupBy('medicines.id', 'medicines.medicine_name', 'medicine_purchase_details.medicine_id')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();
        } else {
            $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');
            $topPurchaseProducts = DB::table('medicine_purchase_details')
                ->whereBetween('medicine_purchase_details.created_at',
                    [
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth(),
                    ])
                ->where('medicine_purchase_details.warehouse_id', $warehouse_id)
                ->leftJoin('medicines', 'medicines.id', '=', 'medicine_purchase_details.medicine_id')
                ->select('medicines.id', 'medicines.medicine_name', 'medicine_purchase_details.medicine_id',
                    DB::raw('SUM(medicine_purchase_details.quantity) as total'), DB::raw('COUNT(medicine_purchase_details.medicine_id) as count'))
                ->groupBy('medicines.id', 'medicines.medicine_name', 'medicine_purchase_details.medicine_id')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();

        }

        return response()->json($topPurchaseProducts);

    }

    public function topCustomer(Request $request)
    {
        if (auth()->user()->hasrole(['Super Admin', 'Admin'])) {

            $topCustomers = DB::table('outlet_invoices')->whereBetween('outlet_invoices.sale_date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])
                ->leftJoin('customers', 'customers.id', '=', 'outlet_invoices.customer_id')
                ->select('outlet_invoices.customer_id', 'customers.name', 'customers.mobile',
                    DB::raw('SUM(outlet_invoices.payable_amount) as total'), DB::raw('COUNT(outlet_invoices.customer_id) as count'))
                ->groupBy('outlet_invoices.customer_id', 'customers.name','customers.mobile')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();
        } else {
            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');

            $topCustomers = DB::table('outlet_invoices')->whereBetween('outlet_invoices.sale_date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])->where('outlet_invoices.outlet_id', $outlet_id)
                ->leftJoin('customers', 'customers.id', '=', 'outlet_invoices.customer_id')
                ->select('outlet_invoices.customer_id', 'customers.name', 'customers.mobile',
                    DB::raw('SUM(outlet_invoices.payable_amount) as total'), DB::raw('COUNT(outlet_invoices.customer_id) as count'))
                ->groupBy('outlet_invoices.customer_id', 'customers.name','customers.mobile')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();

        }

        return response()->json($topCustomers);

    }

    public function summaryWarehouse()
    {
        $data_arr = $this->summaryHelper->getWarehouseSummary();
        return view('admin.summary.warehouse', compact('data_arr'));
    }

    public function summaryOutlet()
    {
        $data_arr = $this->summaryHelper->getOutletSummary();
        return view('admin.summary.outlet', compact('data_arr'));
    }
}
