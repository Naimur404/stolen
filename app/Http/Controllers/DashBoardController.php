<?php

namespace App\Http\Controllers;

use App\Helpers\SummaryHelper;
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

    protected $summaryHelper;
    function __construct()
    {
        $this->middleware('permission:outlet-summary', ['only' => ['summaryOutlet']]);
        $this->middleware('permission:warehouse-summary', ['only' => ['summaryWarehouse']]);
        $this->summaryHelper = new SummaryHelper();
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
        $data_arr = $this->summaryHelper->getWarehouseSummary();
        return view('admin.summary.warehouse', compact('data_arr'));
    }

    public function summaryOutlet()
    {
        $data_arr = $this->summaryHelper->getOutletSummary();
        return view('admin.summary.outlet', compact('data_arr'));
    }
}
