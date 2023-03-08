<?php

namespace App\Http\Controllers;

use App\Models\MedicinePurchase;
use App\Models\Outlet;
use App\Models\OutletInvoice;
use App\Models\OutletInvoiceDetails;
use App\Models\OutletStock;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    }
    public function medicine_sale_report_submit(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])){

            if($request->customer_id == '' || $request->customer_id == null ){
                $productSales = OutletInvoice::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $title = 'All Outlet Sale Report';
            }else{
                $productSales = OutletInvoice::where('customer_id',$request->customer_id)->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $title = 'All Outlet Sale Report';
            }




        }else{
            $outlet_id = Auth::user()->outlet_id != null  ?  Auth::user()->outlet_id : Outlet::orderby('id','desc')->first('id');
            if($request->customer_id == '' || $request->customer_id == null ){


                $productSales = OutletInvoice::where('outlet_id', '=', $outlet_id)->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $outlet =  Outlet::where('id',$outlet_id)->orderby('id','desc')->first('outlet_name');
                $title = 'All Sale Report For '.$outlet->outlet_name;
            }else{

                $productSales = OutletInvoice::where('customer_id',$request->customer_id)->where('outlet_id', '=', $outlet_id)->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $outlet =  Outlet::where('id',$outlet_id)->orderby('id','desc')->first('outlet_name');
                $title = 'All Sale Report For '.$outlet->outlet_name;

            }


        }


          return view('admin.report.medicine_sale_report2', compact('start_date', 'end_date', 'productSales','title'));
    }


    public function medicine_sale_report_details(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])){


                $productSales = OutletInvoice::whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $title = 'All Outlet Sale Report';





        }else{
            $outlet_id = Auth::user()->outlet_id != null  ?  Auth::user()->outlet_id : Outlet::orderby('id','desc')->first('id');



                $productSales = OutletInvoice::where('outlet_id', '=', $outlet_id)->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $outlet =  Outlet::where('id',$outlet_id)->orderby('id','desc')->first('outlet_name');
                $title = 'All Sale Report For '.$outlet->outlet_name;



        }


          return view('admin.report.medicine_sale_report_details', compact('start_date', 'end_date', 'productSales','title'));
    }

    public function medicine_purchase_report_submit(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])){

            $productSales = MedicinePurchase::whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'All Warehouse Purchase Report';


        }else{
            $warehouse_id = Auth::user()->warehouse_id != null  ?  Auth::user()->warehouse_id : Warehouse::orderby('id','desc')->first('id');
            $productSales = MedicinePurchase::where('warehouse_id', '=', $warehouse_id)->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $warehouse =  Warehouse::where('id',$warehouse_id)->orderby('id','desc')->first('warehouse_name');
            $title = 'All Purchase Report For '.$warehouse->warehouse_name;

        }


          return view('admin.report.medicine_purchase_report2', compact('start_date', 'end_date', 'productSales','title'));
    }



    public function warehouse_stock_report_submit(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])){
            if($request->medicine_id == '' || $request->medicine_id == null ){
                $productSales = WarehouseStock::whereDate('created_at', '>=', $start_date)->where('quantity','>','0')
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $title = 'All Warehouse Stock Report';
            }else{
                $productSales = WarehouseStock::where('medicine_id',$request->medicine_id)->whereDate('created_at', '>=', $start_date)->where('quantity','>','0')
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $title = 'All Warehouse Stock Report';
            }



        }else{
            $warehouse_id = Auth::user()->warehouse_id != null  ?  Auth::user()->warehouse_id : Warehouse::orderby('id','desc')->first('id');

            if($request->medicine_id == '' || $request->medicine_id == null ){


                $productSales = WarehouseStock::where('warehouse_id', '=', $warehouse_id)->where('quantity','>','0')->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $outlet =  Warehouse::where('id',$warehouse_id)->orderby('id','desc')->first('warehouse_name');
                $title = 'All Stock Report '.$outlet->warehouse_name;
            }else{

                $productSales = WarehouseStock::where('medicine_id',$request->medicine_id)->where('warehouse_id', '=', $warehouse_id)->where('quantity','>','0')->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $outlet =  Warehouse::where('id',$warehouse_id)->orderby('id','desc')->first('warehouse_name');
                $title = 'All Stock Report '.$outlet->warehouse_name;
            }



        }


          return view('admin.report.warehouse_stock_report2', compact('start_date', 'end_date', 'productSales','title'));
    }



    public function outlet_stock_report_submit(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])){
            if($request->medicine_id == '' || $request->medicine_id == null ){
                $productSales = OutletStock::whereDate('created_at', '>=', $start_date)->where('quantity','>','0')
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $title = 'All Outlet Stock Report';
            }else{
                $productSales = OutletStock::where('medicine_id',$request->medicine_id)->whereDate('created_at', '>=', $start_date)->where('quantity','>','0')
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $title = 'All Outlet Stock Report';
            }



        }else{
            $outlet_id = Auth::user()->outlet_id != null  ?  Auth::user()->outlet_id : outlet::orderby('id','desc')->first('id');

            if($request->medicine_id == '' || $request->medicine_id == null ){


                $productSales = OutletStock::where('outlet_id', '=', $outlet_id)->where('quantity','>','0')->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $outlet =  outlet::where('id',$outlet_id)->orderby('id','desc')->first('outlet_name');
                $title = 'All Stock Report '.$outlet->outlet_name;
            }else{

                $productSales = OutletStock::where('medicine_id',$request->medicine_id)->where('outlet_id', '=', $outlet_id)->where('quantity','>','0')->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
                $outlet =  outlet::where('id',$outlet_id)->orderby('id','desc')->first('outlet_name');
                $title = 'All Stock Report '.$outlet->outlet_name;
            }

        }

          return view('admin.report.outlet_stock_report2', compact('start_date', 'end_date', 'productSales','title'));
    }

    public function index(Request $request){
        $outlet_id = Auth::user()->outlet_id != null ? Auth::user()->outlet_id : Outlet::orderby('id', 'desc')->first('id');

        $search = $request->search;
            if(Auth::user()->hasRole(['Super Admin', 'Admin'])){

                    $Users = User::limit(10)->pluck('name', 'id');

            }else{

                    $Users = User::where('outlet_id',$outlet_id)->where('name', 'like', '%' . $search . '%')->limit(10)->pluck('name', 'id');
            }
             $payment_method = PaymentMethod::pluck('method_name','id');

            return view('admin.report.report',compact('Users','payment_method'));

    }


    public function medicine_sale_report_details1($id){


               $productSales = OutletInvoiceDetails::where('outlet_invoice_id',$id)->get();
               $title = 'Invoice Report Details';

            return view('admin.report.sale_report_details',compact('title','productSales'));

    }

    public function medicine_sale_report_by_user(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);
        $user =  User::where('id',$request->user_id)->orderby('id','desc')->first('name');
        if (Auth::user()->hasRole(['Super Admin', 'Admin'])){
            $productSales = OutletInvoice::where('added_by',$request->user_id)->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'Sale By '.$user->name;
        }else{
            $outlet_id = Auth::user()->outlet_id != null  ?  Auth::user()->outlet_id : outlet::orderby('id','desc')->first('id');
            $productSales = OutletInvoice::where('outlet_id',$outlet_id)->where('added_by',$request->user_id)->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'Sale By '.$user->name;

        }
          return view('admin.report.medicine_sale_report_by_user', compact('start_date', 'end_date', 'productSales','title'));


    }
    public function medicine_sale_report_by_payment(Request $request)
    {
        $input = $request->all();
        $start_date = Carbon::parse($input['start_date']);
        $end_date = Carbon::parse($input['end_date']);



        $paymnt =  PaymentMethod::where('id',$request->paymemt_id)->orderby('id','desc')->first('method_name');
        if (Auth::user()->hasRole(['Super Admin', 'Admin'])){
            $productSales = OutletInvoice::where('payment_method_id',$request->payment_id)->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'Sale By '.$paymnt;
        }else{
            $outlet_id = Auth::user()->outlet_id != null  ?  Auth::user()->outlet_id : outlet::orderby('id','desc')->first('id');
            $productSales = OutletInvoice::where('outlet_id',$outlet_id)->where('payment_method_id',$request->payment_id)->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)->orderBy('id', 'asc')->get();
            $title = 'Sale By '.$paymnt;

        }

          return view('admin.report.medicine_sale_report_by_payment', compact('start_date', 'end_date', 'productSales','title'));
    }

}



