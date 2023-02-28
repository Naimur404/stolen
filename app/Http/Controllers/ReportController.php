<?php

namespace App\Http\Controllers;

use App\Models\MedicinePurchase;
use App\Models\Outlet;
use App\Models\OutletInvoice;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
   
    function __construct()
     {

         $this->middleware('permission:sale.report', ['only' => ['medicine_sale_report_form']]);
         $this->middleware('permission:sale_report.search', ['only' => ['medicine_sale_report_submit']]);
         $this->middleware('permission:purchase.report', ['only' => ['medicine_purchase_report_form']]);
         $this->middleware('permission:purchase_report.search', ['only' => ['medicine_purchase_report_submit']]);
     }
    public function medicine_sale_report_form()
    {
        $title = 'Medicine Sale Report';
        return view('admin.report.medicine_sale_report',compact('title'));
    }
    public function medicine_sale_report_submit(Request $request)
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


          return view('admin.report.medicine_sale_report', compact('start_date', 'end_date', 'productSales','title'));
    }

    public function medicine_purchase_report_form()
    {
        $title = 'Medicine Purchase Report';
        return view('admin.report.medicine_purchase_report',compact('title'));
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


          return view('admin.report.medicine_purchase_report', compact('start_date', 'end_date', 'productSales','title'));
    }
}
