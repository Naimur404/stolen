<?php

namespace App\Http\Controllers;
use App\Models\MedicinePurchase;
use App\Models\Outlet;
use App\Models\OutletInvoice;
use App\Models\OutletInvoiceDetails;
use App\Models\OutletStock;
use App\Models\PaymentMethod;
use App\Models\SalesReturn;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\SupplierHasManufacturer;
use App\Models\Supplier;
use Carbon\Carbon;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Medicine;

class ReportController2 extends Controller
{
    function __construct()
    {

        $this->middleware('permission:sale_report.search', ['only' => ['medicine_sale_report_submit']]);

        $this->middleware('permission:purchase_report.search', ['only' => ['medicine_purchase_report_submit']]);

        $this->middleware('permission:outlet-stock.search', ['only' => ['outlet_stock_report_submit']]);

        $this->middleware('permission:warehouse-stock.search', ['only' => ['warehouse_stock_report_submit']]);

        $this->middleware('permission:sale_report_by_payment.search', ['only' => ['medicine_sale_report_by_payment']]);

        $this->middleware('permission:sale_report_by_user.search', ['only' => ['medicine_sale_report_by_user']]);
        $this->middleware('permission:sale_report_details.search', ['only' => ['medicine_sale_report_details']]);

        $this->middleware('permission:distribute_medicine_report_for_outlet.search', ['only' => ['distribute_medicine_report']]);
        $this->middleware('permission:distribute_medicine_report_for_warehouse.search', ['only' => ['distribute_medicine_report2']]);
        $this->middleware('permission:stock_request_report_for_outlet.search', ['only' => ['stock_request_report']]);
        $this->middleware('permission:stock_request_report_for_warehouse.search', ['only' => ['stock_request_report2']]);
        $this->middleware('permission:return_medicine_report_for_outlet.search', ['only' => ['return_medicine_report']]);
        $this->middleware('permission:return_medicine_report_for_warehouse.search', ['only' => ['return_medicine_report2']]);
        $this->middleware('permission:sale_return_report.search', ['only' => ['medicine_sale_return']]);
        $this->middleware('permission:category-wise-report', ['only' => ['category_wise_report']]);

        $this->middleware('permission:category-wise-report-alert', ['only' => ['category_wise_report_alert']]);
        $this->middleware('permission:category-wise-report-alert-submit', ['only' => ['category_wise_report_alert_submit']]);
        $this->middleware('permission:supplier_wise_sale_report.search', ['only' => ['supplier_wise_sale']]);


        $this->middleware('permission:supplier_wise_stock_report_outlet.search', ['only' => ['supplier_wise_stock_outlet']]);
        $this->middleware('permission:supplier_wise_stock_report_warehouse.search', ['only' => ['supplier_wise_stock_warehouse']]);
        $this->middleware('permission:manufacturer_wise_stock_report_outlet.search', ['only' => ['manufacturer_wise_stock_outlet']]);
        $this->middleware('permission:manufacturer_wise_stock_report_warehouse.search', ['only' => ['manufacturer_wise_stock_warehouse']]);
        $this->middleware('permission:profit_loss.report', ['only' => ['profit_loss']]);

        $this->middleware('permission:expiry-wise-report-outlet.submit', ['only' => ['expiryDate1']]);
        $this->middleware('permission:expiry-wise-report-warehouse.submit', ['only' => ['expiryDate']]);

        $this->middleware('permission:best_selling.search', ['only' => ['bestSelling']]);
        $this->middleware('permission:slow_selling.search', ['only' => ['slowSelling']]);
        $this->middleware('permission:redeem_point_report_submit', ['only' => ['redeemPointReport']]);


        $this->middleware('permission:medicine-sale.report', ['only' => ['sale_report_medicine_submit']]);
        $this->middleware('permission:not-sold-medicine-submit.report', ['only' => ['notSoldMedicine']]);

    }

    public function medicine_sale_report_submit(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {

            if ($request->customer_id == '' || $request->customer_id == null) {
                $productSales = OutletInvoice::whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $title = 'All Outlet Sale Report';
            } else {
                $productSales = OutletInvoice::where('customer_id', $request->customer_id)->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $title = 'All Outlet Sale Report';
            }
        } else {
            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
            if ($request->customer_id == '' || $request->customer_id == null) {


                $productSales = OutletInvoice::where('outlet_id', '=', $outlet_id)->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $outlet = Outlet::where('id', $outlet_id)->orderby('id', 'desc')->first('outlet_name');
                $title = 'All Sale Report For ' . $outlet->outlet_name;
            } else {

                $productSales = OutletInvoice::where('customer_id', $request->customer_id)->where('outlet_id', '=', $outlet_id)->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $outlet = Outlet::where('id', $outlet_id)->orderby('id', 'desc')->first('outlet_name');
                $title = 'All Sale Report For ' . $outlet->outlet_name;
            }
        }


        return view('admin.report.medicine_sale_report2', compact('start_date', 'end_date', 'productSales', 'title'));
    }


    public function medicine_sale_report_details(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {


            $productSales = OutletInvoice::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'All Outlet Sale Report';
        } else {
            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');

            $productSales = OutletInvoice::where('outlet_id', '=', $outlet_id)->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $outlet = Outlet::where('id', $outlet_id)->orderby('id', 'desc')->first('outlet_name');
            $title = 'All Sale Report For ' . $outlet->outlet_name;
        }


        return view('admin.report.medicine_sale_report_details', compact('start_date', 'end_date', 'productSales', 'title'));
    }

    public function medicine_purchase_report_submit(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {

            $productSales = MedicinePurchase::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'All Warehouse Purchase Report';
        } else {
            $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');
            $productSales = MedicinePurchase::where('warehouse_id', '=', $warehouse_id)->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $warehouse = Warehouse::where('id', $warehouse_id)->orderby('id', 'desc')->first('warehouse_name');
            $title = 'All Purchase Report For ' . $warehouse->warehouse_name;
        }


        return view('admin.report.medicine_purchase_report2', compact('start_date', 'end_date', 'productSales', 'title'));
    }


    public function warehouse_stock_report_submit(Request $request)
    {
        $input = $request->all();


        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {
            if ($request->medicine_id == '' || $request->medicine_id == null) {
                $productSales = WarehouseStock::where('quantity', '>', '0')
                    ->orderBy('id', 'asc')->get();
                $title = 'All Warehouse Stock Report';
            } else {
                $productSales = WarehouseStock::where('medicine_id', $request->medicine_id)->where('quantity', '>', '0')
                    ->orderBy('id', 'asc')->get();
                $title = 'All Warehouse Stock Report';
            }
        } else {
            $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');

            if ($request->medicine_id == '' || $request->medicine_id == null) {


                $productSales = WarehouseStock::where('warehouse_id', '=', $warehouse_id)->where('quantity', '>', '0')
                   ->orderBy('id', 'asc')->get();
                $outlet = Warehouse::where('id', $warehouse_id)->orderby('id', 'desc')->first('warehouse_name');
                $title = 'All Stock Report ' . $outlet->warehouse_name;
            } else {

                $productSales = WarehouseStock::where('medicine_id', $request->medicine_id)->where('warehouse_id', '=', $warehouse_id)->where('quantity', '>', '0')
                    ->orderBy('id', 'asc')->get();
                $outlet = Warehouse::where('id', $warehouse_id)->orderby('id', 'desc')->first('warehouse_name');
                $title = 'All Stock Report ' . $outlet->warehouse_name;
            }
        }


        return view('admin.report.warehouse_stock_report2', compact('productSales', 'title'));
    }


    public function outlet_stock_report_submit(Request $request)
    {
        $input = $request->all();


        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {
            if ($request->medicine_id == '' || $request->medicine_id == null) {
                $productSales = OutletStock::where('quantity', '>', '0')
                    ->orderBy('id', 'asc')->get();
                $title = 'All Outlet Stock Report';
            } else {
                $productSales = OutletStock::where('medicine_id', $request->medicine_id)->where('quantity', '>', '0')
                   ->orderBy('id', 'asc')->get();
                $title = 'All Outlet Stock Report';
            }
        } else {
            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : outlet::orderby('id', 'desc')->first('id');

            if ($request->medicine_id == '' || $request->medicine_id == null) {


                $productSales = OutletStock::where('outlet_id', '=', $outlet_id)->where('quantity', '>', '0')
                   ->orderBy('id', 'asc')->get();
                $outlet = outlet::where('id', $outlet_id)->orderby('id', 'desc')->first('outlet_name');
                $title = 'All Stock Report ' . $outlet->outlet_name;
            } else {

                $productSales = OutletStock::where('medicine_id', $request->medicine_id)->where('outlet_id', '=', $outlet_id)->where('quantity', '>', '0')
                    ->orderBy('id', 'asc')->get();
                $outlet = outlet::where('id', $outlet_id)->orderby('id', 'desc')->first('outlet_name');
                $title = 'All Stock Report ' . $outlet->outlet_name;
            }
        }

        return view('admin.report.outlet_stock_report2', compact('productSales', 'title'));
    }

    public function index(Request $request)
    {
        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
        $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');
        $supplier = Supplier::pluck('supplier_name', 'id');

        $search = $request->search;
        if (Auth::user()->hasRole('Super Admin')) {
            $outlet = Outlet::limit(10)->pluck('outlet_name', 'id');
            $Users = User::pluck('name', 'id');
            $warehouse = Warehouse::pluck('warehouse_name', 'id');
            $category = Category::pluck('category_name', 'id');
        } elseif (Auth::user()->hasRole('Admin')) {

            $warehouse = Warehouse::where('id', $warehouse_id)->pluck('warehouse_name', 'id');
            $Users = User::pluck('name', 'id');
            $category = Category::pluck('category_name', 'id');
            $outlet = Outlet::where('id', $outlet_id)->limit(10)->pluck('outlet_name', 'id');
        } else {

            $Users = User::where('outlet_id', $outlet_id)->where('name', 'like', '%' . $search . '%')->limit(10)->pluck('name', 'id');
            $outlet = Outlet::where('id', $outlet_id)->limit(10)->pluck('outlet_name', 'id');
            $category = Category::pluck('category_name', 'id');
            $warehouse = Warehouse::where('id', $warehouse_id)->pluck('warehouse_name', 'id');
        }
        $payment_method = PaymentMethod::pluck('method_name', 'id');

        return view('admin.report.report', compact('Users', 'payment_method', 'outlet', 'warehouse', 'category', 'supplier'));
    }


    public function medicine_sale_report_details1($id)
    {


        $productSales = OutletInvoiceDetails::where('outlet_invoice_id', $id)->get();
        $title = 'Invoice Report Details';

        return view('admin.report.sale_report_details', compact('title', 'productSales'));
    }



    public function sale_report_medicine_submit(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);
        $medicine = Medicine::where('id', $request->medicine_id)->orderby('id', 'desc')->first('medicine_name');
        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {
            $productSales = DB::table('outlet_invoice_details')->where('medicine_id', $request->medicine_id)->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'Medicine Name ' . $medicine->medicine_name;
        } else {
            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : outlet::orderby('id', 'desc')->first('id');
            $productSales = $productSales = DB::table('outlet_invoice_details')->where('outlet_id', $outlet_id)->where('medicine_id', $request->medicine_id)->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();


            $title = 'Medicine Name ' . $medicine->medicine_name;
        }
        return view('admin.report.medicine_sale_report_by_medicine', compact('start_date', 'end_date', 'productSales', 'title'));
    }


    public function medicine_sale_report_by_user(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);
        $user = User::where('id', $request->user_id)->orderby('id', 'desc')->first('name');
        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {
            $productSales = OutletInvoice::where('added_by', $request->user_id)->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'Sale By ' . $user->name;
        } else {
            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : outlet::orderby('id', 'desc')->first('id');
            $productSales = OutletInvoice::where('outlet_id', $outlet_id)->where('added_by', $request->user_id)->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'Sale By ' . $user->name;
        }
        return view('admin.report.medicine_sale_report_by_user', compact('start_date', 'end_date', 'productSales', 'title'));
    }

    public function medicine_sale_report_by_payment(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);


        $paymnt = PaymentMethod::where('id', $request->paymemt_id)->orderby('id', 'desc')->first('method_name');
        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {
            $productSales = OutletInvoice::where('payment_method_id', $request->payment_id)->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'Sale By ' . $paymnt;
        } else {
            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : outlet::orderby('id', 'desc')->first('id');
            $productSales = OutletInvoice::where('outlet_id', $outlet_id)->where('payment_method_id', $request->payment_id)->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'Sale By ' . $paymnt;
        }

        return view('admin.report.medicine_sale_report_by_payment', compact('start_date', 'end_date', 'productSales', 'title'));
    }

    public function distribute_medicine_report(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        // $productSales = MedicineDistribute::where('outlet_id',$request->outlet_id)->whereDate('created_at', '>=', $start_date)
        // ->whereDate('created_at', '<=', $end_date)->with(['medicinedistributesdetails'])->get();

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {

            if ($request->outlet_id == '' || $request->outlet_id == null) {


                $productSales = DB::table('medicine_distributes')->where('medicine_distributes.has_sent', 1)->whereDate('medicine_distributes.created_at', '>=', $start_date)
                    ->whereDate('medicine_distributes.created_at', '<=', $end_date)->leftJoin('medicine_distribute_details', 'medicine_distributes.id', '=', 'medicine_distribute_details.medicine_distribute_id')->select('medicine_distributes.id as mdid', 'medicine_distributes.*', 'medicine_distribute_details.*');
                if ($request->medicine_id == '' || $request->medicine_id == null) {
                    $productSales = $productSales->get();
                } else {
                    $productSales = $productSales->where('medicine_distribute_details.medicine_id', $request->medicine_id)->get();
                }
                $title = 'Distribute Medicine Report';
            } else {
                $productSales = DB::table('medicine_distributes')->where('medicine_distributes.has_sent', 1)->where('medicine_distributes.outlet_id', $request->outlet_id)->whereDate('medicine_distributes.created_at', '>=', $start_date)
                    ->whereDate('medicine_distributes.created_at', '<=', $end_date)->leftJoin('medicine_distribute_details', 'medicine_distributes.id', '=', 'medicine_distribute_details.medicine_distribute_id')->select('medicine_distributes.id as mdid', 'medicine_distributes.*', 'medicine_distribute_details.*');
                if ($request->medicine_id == '' || $request->medicine_id == null) {
                    $productSales = $productSales->get();
                } else {
                    $productSales = $productSales->where('medicine_distribute_details.medicine_id', $request->medicine_id)->get();
                }
                $title = 'Distribute Medicine Report';
            }
        } else {

            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : outlet::orderby('id', 'desc')->first('id');
            if ($request->outlet_id == '' || $request->outlet_id == null) {
                $productSales = DB::table('medicine_distributes')->where('medicine_distributes.has_sent', 1)->where('medicine_distributes.outlet_id', $outlet_id)->whereDate('medicine_distributes.created_at', '>=', $start_date)
                    ->whereDate('medicine_distributes.created_at', '<=', $end_date)->leftJoin('medicine_distribute_details', 'medicine_distributes.id', '=', 'medicine_distribute_details.medicine_distribute_id')->select('medicine_distributes.id as mdid', 'medicine_distributes.*', 'medicine_distribute_details.*');
                if ($request->medicine_id == '' || $request->medicine_id == null) {
                    $productSales = $productSales->get();
                } else {
                    $productSales = $productSales->where('medicine_distribute_details.medicine_id', $request->medicine_id)->get();
                }
                $title = 'All Distribute Medicine Report';
            } else {
                $productSales = DB::table('medicine_distributes')->where('medicine_distributes.has_sent', 1)->where('medicine_distributes.outlet_id', $request->outlet_id)->whereDate('medicine_distributes.created_at', '>=', $start_date)
                    ->whereDate('medicine_distributes.created_at', '<=', $end_date)->leftJoin('medicine_distribute_details', 'medicine_distributes.id', '=', 'medicine_distribute_details.medicine_distribute_id')->select('medicine_distributes.id as mdid', 'medicine_distributes.*', 'medicine_distribute_details.*');
                if ($request->medicine_id == '' || $request->medicine_id == null) {
                    $productSales = $productSales->get();
                } else {
                    $productSales = $productSales->where('medicine_distribute_details.medicine_id', $request->medicine_id)->get();
                }
                $title = 'All Distribute Medicine Report';
            }
        }


        return view('admin.report.medicine_distruburte_report', compact('start_date', 'end_date', 'productSales', 'title'));
    }


    public function stock_request_report(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        // $productSales = MedicineDistribute::where('outlet_id',$request->outlet_id)->whereDate('created_at', '>=', $start_date)
        // ->whereDate('created_at', '<=', $end_date)->with(['medicinedistributesdetails'])->get();

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {

            if ($request->outlet_id == '' || $request->outlet_id == null) {


                $productSales = DB::table('stock_requests')->where('stock_requests.has_sent', 1)->where('stock_requests.has_accepted', 1)->whereDate('stock_requests.created_at', '>=', $start_date)
                    ->whereDate('stock_requests.created_at', '<=', $end_date)->leftJoin('stock_request_details', 'stock_requests.id', '=', 'stock_request_details.stock_request_id')->select('stock_requests.id as mdid', 'stock_requests.*', 'stock_request_details.*');
                if ($request->medicine_id == '' || $request->medicine_id == null) {
                    $productSales = $productSales->get();
                } else {
                    $productSales = $productSales->where('stock_request_details.medicine_id', $request->medicine_id)->get();
                }
                $title = 'Stock Request Report';
            } else {

                $productSales = DB::table('stock_requests')->where('stock_requests.has_sent', 1)->where('stock_requests.has_accepted', 1)->where('stock_requests.outlet_id', $request->outlet_id)->whereDate('stock_requests.created_at', '>=', $start_date)
                    ->whereDate('stock_requests.created_at', '<=', $end_date)->leftJoin('stock_request_details', 'stock_requests.id', '=', 'stock_request_details.stock_request_id')->select('stock_requests.id as mdid', 'stock_requests.*', 'stock_request_details.*');
                if ($request->medicine_id == '' || $request->medicine_id == null) {
                    $productSales = $productSales->get();
                } else {
                    $productSales = $productSales->where('stock_request_details.medicine_id', $request->medicine_id)->get();
                }
                $title = 'Stock Request Report';
            }
        } else {

            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : outlet::orderby('id', 'desc')->first('id');
            if ($request->outlet_id == '' || $request->outlet_id == null) {
                $productSales = DB::table('stock_requests')->where('stock_requests.has_accepted', 1)->where('stock_requests.has_sent', 1)->where('stock_requests.outlet_id', $outlet_id)->whereDate('stock_requests.created_at', '>=', $start_date)
                    ->whereDate('stock_requests.created_at', '<=', $end_date)->leftJoin('stock_request_details', 'stock_requests.id', '=', 'stock_request_details.stock_request_id')->select('stock_requests.id as mdid', 'stock_requests.*', 'stock_request_details.*');
                if ($request->medicine_id == '' || $request->medicine_id == null) {
                    $productSales = $productSales->get();
                } else {
                    $productSales = $productSales->where('stock_request_details.medicine_id', $request->medicine_id)->get();
                }
                $title = 'All Stock Request Report';
            } else {
                $productSales = DB::table('stock_requests')->where('stock_requests.has_accepted', 1)->where('stock_requests.has_sent', 1)->where('stock_requests.outlet_id', $request->outlet_id)->whereDate('stock_requests.created_at', '>=', $start_date)
                    ->whereDate('stock_requests.created_at', '<=', $end_date)->leftJoin('stock_request_details', 'stock_requests.id', '=', 'stock_request_details.stock_request_id')->select('stock_requests.id as mdid', 'stock_requests.*', 'stock_request_details.*');
                if ($request->medicine_id == '' || $request->medicine_id == null) {
                    $productSales = $productSales->get();
                } else {
                    $productSales = $productSales->where('stock_request_details.medicine_id', $request->medicine_id)->get();
                }
                $title = 'All Stock Request Report';
            }
        }


        return view('admin.report.stock_request_report', compact('start_date', 'end_date', 'productSales', 'title'));
    }


    //for warehouse manager
    public function distribute_medicine_report2(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        // $productSales = MedicineDistribute::where('outlet_id',$request->outlet_id)->whereDate('created_at', '>=', $start_date)
        // ->whereDate('created_at', '<=', $end_date)->with(['medicinedistributesdetails'])->get();


        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {


            $productSales = DB::table('medicine_distributes')->where('medicine_distributes.has_sent', 1)->whereDate('medicine_distributes.created_at', '>=', $start_date)
                ->whereDate('medicine_distributes.created_at', '<=', $end_date)->leftJoin('medicine_distribute_details', 'medicine_distributes.id', '=', 'medicine_distribute_details.medicine_distribute_id')->select('medicine_distributes.id as mdid', 'medicine_distributes.*', 'medicine_distribute_details.*');
            if ($request->medicine_id == '' || $request->medicine_id == null) {
                $productSales = $productSales->get();
            } else {
                $productSales = $productSales->where('medicine_distribute_details.medicine_id', $request->medicine_id)->get();
            }
            $title = 'Distribute Medicine Report';
        } else {

            $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');
            $productSales = DB::table('medicine_distributes')->where('warehouse_id', $warehouse_id)->where('medicine_distributes.has_sent', 1)->whereDate('medicine_distributes.created_at', '>=', $start_date)
                ->whereDate('medicine_distributes.created_at', '<=', $end_date)->leftJoin('medicine_distribute_details', 'medicine_distributes.id', '=', 'medicine_distribute_details.medicine_distribute_id')->select('medicine_distributes.id as mdid', 'medicine_distributes.*', 'medicine_distribute_details.*');
            if ($request->medicine_id == '' || $request->medicine_id == null) {
                $productSales = $productSales->get();
            } else {
                $productSales = $productSales->where('medicine_distribute_details.medicine_id', $request->medicine_id)->get();
            }
            $title = 'Distribute Medicine Report';
        }


        return view('admin.report.medicine_distruburte_report', compact('start_date', 'end_date', 'productSales', 'title'));
    }


    public function stock_request_report2(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        // $productSales = MedicineDistribute::where('outlet_id',$request->outlet_id)->whereDate('created_at', '>=', $start_date)
        // ->whereDate('created_at', '<=', $end_date)->with(['medicinedistributesdetails'])->get();

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {

            $productSales = DB::table('stock_requests')->where('stock_requests.has_sent', 1)->where('stock_requests.has_accepted', 1)->whereDate('stock_requests.created_at', '>=', $start_date)
                ->whereDate('stock_requests.created_at', '<=', $end_date)->leftJoin('stock_request_details', 'stock_requests.id', '=', 'stock_request_details.stock_request_id')->select('stock_requests.id as mdid', 'stock_requests.*', 'stock_request_details.*');
            if ($request->medicine_id == '' || $request->medicine_id == null) {
                $productSales = $productSales->get();
            } else {
                $productSales = $productSales->where('stock_request_details.medicine_id', $request->medicine_id)->get();
            }
            $title = 'Stock Request Report';
        } else {
            $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');
            $productSales = DB::table('stock_requests')->where('warehouse_id', $warehouse_id)->where('stock_requests.has_sent', 1)->where('stock_requests.has_accepted', 1)->whereDate('stock_requests.created_at', '>=', $start_date)
                ->whereDate('stock_requests.created_at', '<=', $end_date)->leftJoin('stock_request_details', 'stock_requests.id', '=', 'stock_request_details.stock_request_id')->select('stock_requests.id as mdid', 'stock_requests.*', 'stock_request_details.*');
            if ($request->medicine_id == '' || $request->medicine_id == null) {
                $productSales = $productSales->get();
            } else {
                $productSales = $productSales->where('stock_request_details.medicine_id', $request->medicine_id)->get();
            }
            $title = 'Stock Request Report';
        }


        return view('admin.report.stock_request_report', compact('start_date', 'end_date', 'productSales', 'title'));
    }


    //retun medicine

    public function return_medicine_report(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        // $productSales = MedicineDistribute::where('outlet_id',$request->outlet_id)->whereDate('created_at', '>=', $start_date)
        // ->whereDate('created_at', '<=', $end_date)->with(['medicinedistributesdetails'])->get();

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {

            if ($request->outlet_id == '' || $request->outlet_id == null) {


                $productSales = DB::table('warehouse_returns')->whereDate('warehouse_returns.created_at', '>=', $start_date)
                    ->whereDate('warehouse_returns.created_at', '<=', $end_date)->leftJoin('warehouse_return_details', 'warehouse_returns.id', '=', 'warehouse_return_details.warehouse_return_id')->select('warehouse_returns.id as mdid', 'warehouse_returns.*', 'warehouse_return_details.*')->where('warehouse_return_details.has_received', 1);;
                if ($request->medicine_id == '' || $request->medicine_id == null) {
                    $productSales = $productSales->get();
                } else {
                    $productSales = $productSales->where('warehouse_return_details.medicine_id', $request->medicine_id)->get();
                }
                $title = 'Return Medicine Report';
            } else {
                $productSales = DB::table('warehouse_returns')->where('warehouse_returns.outlet_id', $request->outlet_id)->whereDate('warehouse_returns.created_at', '>=', $start_date)
                    ->whereDate('warehouse_returns.created_at', '<=', $end_date)->leftJoin('warehouse_return_details', 'warehouse_returns.id', '=', 'warehouse_return_details.warehouse_return_id')->select('warehouse_returns.id as mdid', 'warehouse_returns.*', 'warehouse_return_details.*')->where('warehouse_return_details.has_received', 1);;
                if ($request->medicine_id == '' || $request->medicine_id == null) {
                    $productSales = $productSales->get();
                } else {
                    $productSales = $productSales->where('warehouse_return_details.medicine_id', $request->medicine_id)->get();
                }
                $title = 'Return Medicine Report';
            }
        } else {

            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : outlet::orderby('id', 'desc')->first('id');
            if ($request->outlet_id == '' || $request->outlet_id == null) {
                $productSales = DB::table('warehouse_returns')->where('warehouse_returns.outlet_id', $outlet_id)->whereDate('warehouse_returns.created_at', '>=', $start_date)
                    ->whereDate('warehouse_returns.created_at', '<=', $end_date)->leftJoin('warehouse_return_details', 'warehouse_returns.id', '=', 'warehouse_return_details.warehouse_return_id')->select('warehouse_returns.id as mdid', 'warehouse_returns.*', 'warehouse_return_details.*')->where('warehouse_return_details.has_received', 1);
                if ($request->medicine_id == '' || $request->medicine_id == null) {
                    $productSales = $productSales->get();
                } else {
                    $productSales = $productSales->where('warehouse_return_details.medicine_id', $request->medicine_id)->get();
                }
                $title = 'Return Medicine Report';
            } else {
                $productSales = DB::table('warehouse_returns')->where('warehouse_returns.outlet_id', $request->outlet_id)->whereDate('warehouse_returns.created_at', '>=', $start_date)
                    ->whereDate('warehouse_returns.created_at', '<=', $end_date)->leftJoin('warehouse_return_details', 'warehouse_returns.id', '=', 'warehouse_return_details.warehouse_return_id')->select('warehouse_returns.id as mdid', 'warehouse_returns.*', 'warehouse_return_details.*')->where('warehouse_return_details.has_received', 1);;
                if ($request->medicine_id == '' || $request->medicine_id == null) {
                    $productSales = $productSales->get();
                } else {
                    $productSales = $productSales->where('warehouse_return_details.medicine_id', $request->medicine_id)->get();
                }
                $title = 'Return Medicine Report';
            }
        }


        return view('admin.report.retun_medicine_report', compact('start_date', 'end_date', 'productSales', 'title'));
    }

    //for warehouse manager


    public function return_medicine_report2(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        // $productSales = MedicineDistribute::where('outlet_id',$request->outlet_id)->whereDate('created_at', '>=', $start_date)
        // ->whereDate('created_at', '<=', $end_date)->with(['medicinedistributesdetails'])->get();


        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {

            $productSales = DB::table('warehouse_returns')->whereDate('warehouse_returns.created_at', '>=', $start_date)
                ->whereDate('warehouse_returns.created_at', '<=', $end_date)->leftJoin('warehouse_return_details', 'warehouse_returns.id', '=', 'warehouse_return_details.warehouse_return_id')->select('warehouse_returns.id as mdid', 'warehouse_returns.*', 'warehouse_return_details.*')->where('warehouse_return_details.has_received', 1);;
            if ($request->medicine_id == '' || $request->medicine_id == null) {
                $productSales = $productSales->get();
            } else {
                $productSales = $productSales->where('warehouse_return_details.medicine_id', $request->medicine_id)->get();
            }
            $title = 'Return Medicine Report';
        } else {
            $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');
            $productSales = DB::table('warehouse_returns')->where('warehouse_id', $warehouse_id)->whereDate('warehouse_returns.created_at', '>=', $start_date)
                ->whereDate('warehouse_returns.created_at', '<=', $end_date)->leftJoin('warehouse_return_details', 'warehouse_returns.id', '=', 'warehouse_return_details.warehouse_return_id')->select('warehouse_returns.id as mdid', 'warehouse_returns.*', 'warehouse_return_details.*')->where('warehouse_return_details.has_received', 1);;
            if ($request->medicine_id == '' || $request->medicine_id == null) {
                $productSales = $productSales->get();
            } else {
                $productSales = $productSales->where('warehouse_return_details.medicine_id', $request->medicine_id)->get();
            }
            $title = 'Return Medicine Report';
        }


        return view('admin.report.retun_medicine_report', compact('start_date', 'end_date', 'productSales', 'title'));
    }

    //outlet manager  sales return

    public function medicine_sale_return(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {


            $productSales = SalesReturn::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'All Outlet Sale Return Report';
        } else {
            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
            $productSales = SalesReturn::where('outlet_id', '=', $outlet_id)->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $outlet = Outlet::where('id', $outlet_id)->orderby('id', 'desc')->first('outlet_name');
            $title = 'All Sale Return Report For ' . $outlet->outlet_name;
        }


        return view('admin.report.slaes_return_report', compact('start_date', 'end_date', 'productSales', 'title'));
    }

    public function category_wise_report(Request $request)
    {
        $input = $request->all();


        if ($request->outlet_id != null && $request->outlet_id != '') {
            $productSales = DB::table('outlet_stocks')->where('outlet_stocks.quantity', '>', '0')->where('outlet_stocks.outlet_id', $request->outlet_id)
                ->leftjoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')->where('medicines.category_id', $request->category_id)
                ->select('outlet_stocks.*', 'medicines.category_id', 'medicines.medicine_name')->get();

            $category = Category::where('id', $request->category_id)->first();
            $title = 'Stock Report For ' . $category->category_name;
        } else {
            $productSales = DB::table('warehouse_stocks')->where('warehouse_stocks.quantity', '>', '0')->where('warehouse_stocks.warehouse_id', $request->warehouse_id)
                ->leftjoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')->where('medicines.category_id', $request->category_id)
                ->select('warehouse_stocks.*', 'medicines.category_id', 'medicines.medicine_name')->get();
            $category = Category::where('id', $request->category_id)->first();
            $title = 'Stock Report For ' . $category->category_name;
        }


        return view('admin.report.category_stock_report', compact('productSales', 'title'));
    }


    public function category_wise_report_alert()


    {
        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
        $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');
        if (Auth::user()->hasRole('Super Admin')) {
            $outlet = Outlet::limit(10)->pluck('outlet_name', 'id');

            $warehouse = Warehouse::pluck('warehouse_name', 'id');
            $category1 = Category::pluck('category_name', 'id');
        } elseif (Auth::user()->hasRole('Admin')) {

            $warehouse = Warehouse::where('id', $warehouse_id)->pluck('warehouse_name', 'id');

            $category1 = Category::pluck('category_name', 'id');
            $outlet = Outlet::where('id', $outlet_id)->limit(10)->pluck('outlet_name', 'id');
        } else {


            $outlet = Outlet::where('id', $outlet_id)->limit(10)->pluck('outlet_name', 'id');
            $category1 = Category::pluck('category_name', 'id');
            $warehouse = Warehouse::where('id', $warehouse_id)->pluck('warehouse_name', 'id');
        }
        $title = 'Medicine Purchase Report';
        return view('admin.report.category_wise_stock_alert', compact('title', 'outlet', 'category1', 'warehouse'));
    }

    public function category_wise_report_alert_submit(Request $request)
    {
        $input = $request->all();
        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');
        $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');
        if (Auth::user()->hasRole('Super Admin')) {
            $outlet = Outlet::limit(10)->pluck('outlet_name', 'id');

            $warehouse = Warehouse::pluck('warehouse_name', 'id');
            $category1 = Category::pluck('category_name', 'id');
        } elseif (Auth::user()->hasRole('Admin')) {

            $warehouse = Warehouse::where('id', $warehouse_id)->pluck('warehouse_name', 'id');

            $category1 = Category::pluck('category_name', 'id');
            $outlet = Outlet::where('id', $outlet_id)->limit(10)->pluck('outlet_name', 'id');
        } else {


            $outlet = Outlet::where('id', $outlet_id)->limit(10)->pluck('outlet_name', 'id');
            $category1 = Category::pluck('category_name', 'id');
            $warehouse = Warehouse::where('id', $warehouse_id)->pluck('warehouse_name', 'id');
        }
        // $start_date = Carbon::parse($input['start_date']);
        // $end_date = Carbon::parse($input['end_date']);

        if ($request->outlet_id != null || $request->outlet_id != '') {
            $productSales = DB::table('outlet_stocks')->where('outlet_stocks.quantity', '>', '0')->where('outlet_stocks.outlet_id', $request->outlet_id)
                ->leftjoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
                ->select('outlet_stocks.*', 'medicines.category_id', 'medicines.medicine_name');
            if ($request->category_id) {
                $total = 0;
                $categorys = Category::whereIn('id', $request->category_id)->get();
                foreach ($categorys as $data) {
                    $total = $total + $data->alert_limit;
                }

                $productSales = $productSales->whereIn('medicines.category_id', $request->category_id);
            } else {
                $total = 0;
                $categorys = Category::get();
                foreach ($categorys as $data) {
                    $total = $total + $data->alert_limit;
                }
            }
            if ($request->manufacturer_id) {


                $productSales = $productSales->whereIn('medicines.manufacturer_id', $request->manufacturer_id);
            }
            $data = $productSales->sum('outlet_stocks.quantity');
            if ($data <= $total) {
                $productSales = $productSales->get();
            } else {
                $productSales = array();
            }

            $category = Category::where('id', $request->category_id)->first();
            $title = 'Category Stock Report Alert';
        } else {
            $productSales = DB::table('warehouse_stocks')->where('warehouse_stocks.quantity', '>', '0')->where('warehouse_stocks.warehouse_id', $request->warehouse_id)
                ->leftjoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')
                ->select('warehouse_stocks.*', 'medicines.category_id', 'medicines.medicine_name');
            if ($request->category_id) {

                $total = 0;
                $categorys = Category::whereIn('id', $request->category_id)->get();
                foreach ($categorys as $data) {
                    $total = $total + $data->alert_limit;
                }

                $productSales = $productSales->whereIn('medicines.category_id', $request->category_id);
            } else {
                $total = 0;
                $categorys = Category::get();
                foreach ($categorys as $data) {
                    $total = $total + $data->alert_limit;
                }
            }

            if ($request->manufacturer_id) {

                $productSales = $productSales->whereIn('medicines.manufacturer_id', $request->manufacturer_id);
            }

            $data = $productSales->sum('warehouse_stocks.quantity');
            if ($data <= $total) {
                $productSales = $productSales->get();
            } else {
                $productSales = array();
            }


            $category = Category::where('id', $request->category_id)->first();
            $title = 'Category Stock Report Alert';

        }


         return view('admin.report.category_wise_stock_alert', compact('productSales', 'title', 'outlet', 'category', 'warehouse', 'category1', 'total'));
    }


    public function supplier_wise_sale(Request $request)
    {
        $input = $request->all();

        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');


        $productSales = DB::table('outlet_invoice_details')->whereDate('outlet_invoice_details.created_at', '>=', $start_date)
            ->whereDate('outlet_invoice_details.created_at', '<=', $end_date)
            ->leftJoin('medicines', 'medicines.id', '=', 'outlet_invoice_details.medicine_id')->select('outlet_invoice_details.*','medicines.manufacturer_id')
            ->get();

        $manu = SupplierHasManufacturer::where('supplier_id', $request->supplier_id)->get();
        $sup = Supplier::where('id', $request->supplier_id)->first();

        $title = $sup->supplier_name . ' Supplier Sale report';

        return view('admin.report.supplier_wise_sales_report', compact('start_date', 'end_date', 'productSales', 'manu', 'title'));
    }

    public function supplier_wise_stock_outlet(Request $request)
    {
        $input = $request->all();


        $productStocks = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $request->outlet_id)
           ->where('outlet_stocks.quantity', '>', '0')->leftJoin('medicines', 'medicines.id', '=', 'outlet_stocks.medicine_id')->select('outlet_stocks.*', 'medicines.manufacturer_id','medicines.medicine_name')->get();

        $manu = SupplierHasManufacturer::where('supplier_id', $request->supplier_id)->get();
        $sup = Supplier::where('id', $request->supplier_id)->first();

        $title = $sup->supplier_name . ' Supplier Medicine Stock report';


        return view('admin.report.supplier_wise_stock_report', compact('productStocks', 'manu', 'title'));
    }

    public function supplier_wise_stock_warehouse(Request $request)
    {
        $input = $request->all();



        $productStocks = DB::table('warehouse_stocks')->where('warehouse_stocks.warehouse_id', $request->warehouse_id)
            ->where('warehouse_stocks.quantity', '>', '0')->leftJoin('medicines', 'medicines.id', '=', 'warehouse_stocks.medicine_id')->select('warehouse_stocks.*', 'medicines.manufacturer_id', 'medicines.medicine_name')->get();

        $manu = SupplierHasManufacturer::where('supplier_id', $request->supplier_id)->get();
        $sup = Supplier::where('id', $request->supplier_id)->first();

        $title = $sup->supplier_name . ' Supplier Medicine Stock report';


        return view('admin.report.supplier_wise_stock_report', compact('productStocks', 'manu', 'title'));
    }


    public function manufacturer_wise_stock_outlet(Request $request)
    {
        $input = $request->all();


        $productStocks = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $request->outlet_id)->
      where('outlet_stocks.quantity', '>', '0')->leftJoin('medicines', 'medicines.id', '=', 'outlet_stocks.medicine_id')->where('medicines.manufacturer_id', $request->manufacturer_id)->select('outlet_stocks.*', 'medicines.manufacturer_id','medicines.medicine_name')->get();
        // dump($productStocks);

        $manu = Manufacturer::where('id', $request->manufacturer_id)->first();

     $title = $manu->manufacturer_name . ' Manufacturer Medicine Stock report';


        return view('admin.report.manufacturer_wise_stock_report', compact( 'productStocks', 'manu', 'title'));
    }

    public function manufacturer_wise_stock_warehouse(Request $request)
    {
        $input = $request->all();



        $productStocks = DB::table('warehouse_stocks')->where('warehouse_stocks.warehouse_id', $request->warehouse_id)
            ->where('warehouse_stocks.quantity', '>', '0')->leftJoin('medicines', 'medicines.id', '=', 'warehouse_stocks.medicine_id')->where('medicines.manufacturer_id', $request->manufacturer_id)->select('warehouse_stocks.*', 'medicines.manufacturer_id', 'medicines.medicine_name')->get();

            $manu = Manufacturer::where('id', $request->manufacturer_id)->first();

            $title = $manu->manufacturer_name . ' Manufacturer Medicine Stock report';


        return view('admin.report.manufacturer_wise_stock_report', compact( 'productStocks', 'manu', 'title'));
    }




    public function profit_loss(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);
        $total_discount = 0;

        // $productSales = MedicineDistribute::where('outlet_id',$request->outlet_id)->whereDate('created_at', '>=', $start_date)
        // ->whereDate('created_at', '<=', $end_date)->with(['medicinedistributesdetails'])->get();

        if (Auth::user()->hasAnyRole(['Super Admin', 'Admin'])) {


            $productSales = DB::table('outlet_invoice_details')->whereDate('outlet_invoice_details.created_at', '>=', $start_date)
                ->whereDate('outlet_invoice_details.created_at', '<=', $end_date)->leftJoin('outlet_stocks', 'outlet_invoice_details.stock_id', '=', 'outlet_stocks.id')->select('outlet_invoice_details.*', 'outlet_stocks.purchase_price')->distinct()->get();
            $title = 'Profit & Loss Report';
            // dump($productSales);
            $total_discount = OutletInvoice::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->sum('total_discount');
        } else {

            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : outlet::orderby('id', 'desc')->first('id');
            $productSales = DB::table('outlet_invoice_details')->whereDate('outlet_invoice_details.created_at', '>=', $start_date)
                ->whereDate('outlet_invoice_details.created_at', '<=', $end_date)->leftJoin('outlet_stocks', 'outlet_invoice_details.stock_id', '=', 'outlet_stocks.id')->select('outlet_invoice_details.*', 'outlet_stocks.purchase_price', 'outlet_stocks.outlet_id')->where('outlet_stocks.outlet_id', $outlet_id)->distinct()->get();
             $total_discount = OutletInvoice::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('outlet_id', $outlet_id)->sum('total_discount');
            $title = 'Profit & Loss Report';
            // dump($productSales);

        }


        return view('admin.report.profit_loss_report', compact('start_date', 'end_date', 'productSales', 'title','total_discount'));
    }


    public function expiryDate(Request $request)
    {
        $input = $request->all();

        $start_date = Carbon::parse($input['start_date']);


        $productSales = WarehouseStock::whereDate('expiry_date', '<', $start_date)
            ->where('quantity', '>', '0')->get();


        $title = 'Expiry Wise Report';


        return view('admin.report.expiry_wise_report_warehouse', compact('start_date', 'productSales', 'title'));
    }

    public function expiryDate1(Request $request)
    {
        $input = $request->all();

        $start_date = Carbon::parse($input['start_date']);
        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {
            $productSales = OutletStock::whereDate('expiry_date', '<', $start_date)
                ->where('quantity', '>', '0')->get();
        } else {
            $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : outlet::orderby('id', 'desc')->first('id');
            $productSales = OutletStock::where('outlet_id', $outlet_id)->whereDate('expiry_date', '<', $start_date)
                ->where('quantity', '>', '0')->get();
        }


        $title = 'Expiry Wise Report';


        return view('admin.report.expiry_wise_report_outlet', compact('start_date', 'productSales', 'title'));
    }

    public function bestSelling(Request $request)
    {

        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        $data = OutletInvoiceDetails::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->orderby('quantity', 'desc')->get();
        return view('admin.report.best_selling', compact('data'));
    }

    public function slowSelling(Request $request)
    {

        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        $data = OutletInvoiceDetails::whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->where('quantity', '>', '1')->orderby('quantity', 'asc')->get();
        return view('admin.report.slow_selling', compact('data'));
    }

    public function notSoldMedicine(Request $request){

        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        $medicine_id = DB::table('outlet_invoice_details')->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->select('medicine_id')->distinct()->pluck('medicine_id')->toArray();
        $not_sold = DB::table('outlet_stocks')->where('quantity', '>', 0)->whereNotIn('medicine_id',$medicine_id)->leftjoin('medicines','outlet_stocks.medicine_id','=','medicines.id')->select('outlet_stocks.*','medicines.medicine_name as medicine_name')->get();
        return view('admin.report.not_sold_medicine', compact('start_date', 'end_date','not_sold'));


    }
   public function redeemPointReport(Request $request){

        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        $redeemSales =   OutletInvoice::where('outlet_id', $request->outlet_id)->where('redeem_point','!=', NULL)->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date)->get();

        return view('admin.report.redeem_point_report', compact('start_date', 'end_date','redeemSales'));
   }
}
