<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Medicine;
use App\Models\MedicineDistribute;
use App\Models\MedicineDistributeDetail;
use App\Models\Outlet;
use App\Models\OutletCheckIn;
use App\Models\OutletStock;
use App\Models\WarehouseStock;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutletStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:outletStock', ['only' => ['outletStock']]);
        $this->middleware('permission:outletStock-price-edit', ['only' => ['edit']]);
        $this->middleware('permission:outletStock-price-update', ['only' => ['update']]);

    }

    public function index()
    {
        if (auth()->user()->hasrole(['Super Admin', 'Admin'])) {

            $outlet = Outlet::pluck('outlet_name', 'id');
            $outlet = new Collection($outlet);
            $outlet->prepend('All Outlet Stock', 'all');

        } else {
            $outlet = Outlet::where('id', Auth::user()->outlet_id)->pluck('outlet_name', 'id');
        }

        return view('admin.medicinestock.OutletStock', compact('outlet'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $manu_price = WarehouseStock::where('warehouse_id', $request->warehouse_id)->where('medicine_id', $request->medicine_id)->where('size', '=', $request->size)->first();
        $data = array(
            'quantity' => (int) $request->quantity,
            'price' => $request->price,
            'outlet_id' => $request->outlet_id,
            'medicine_id' => $request->medicine_id,
            'size' => $request->size,
            'create_date' => $request->create_date,
            'barcode_text' => $request->barcode,
            'warehouse_stock_id' => $request->stock_id ?? null,
            'purchase_price' => $manu_price->purchase_price,

        );

        $check = OutletStock::where('outlet_id', $request->outlet_id)->where('medicine_id', $request->medicine_id)->where('size', '=', $request->size)->first();

        if ($check != null) {
            $stock2 = array(

                'quantity' => (int) $check->quantity + (int) $request->quantity,
                'price' => $request->price,
                'purchase_price' => $manu_price->purchase_price,
            );
            $has_received2 = array(

                'has_received' => '1',

            );
            MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)->where('medicine_id', $request->medicine_id)->where('size', '=', $request->size)->where('create_date', '=', $request->create_date)->update($has_received2);
            OutletStock::where('outlet_id', $request->outlet_id)->where('medicine_id', $request->medicine_id)->where('size', '=', $request->size)->update($stock2);

        } else {
            $has_received2 = array(

                'has_received' => '1',

            );
            MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)->where('medicine_id', $request->medicine_id)->where('size', '=', $request->size)->where('create_date', '=', $request->create_date)->update($has_received2);
            $check = OutletStock::create($data);

        }
        $check2 = MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)->where('has_received', '0')->get();
        if (count($check2) < 1) {
            $has_received = array(
                'has_received' => '1',

            );
            MedicineDistribute::where('id', $request->medicine_distribute_id)->update($has_received);
        }

        try {
            $data = array(
                'outlet_id' => $request->outlet_id,
                'medicine_distribute_id' => $request->medicine_distribute_id,
                'medicine_id' => $request->medicine_id,
                'size' => $request->size,
                'create_date' => $request->create_date,
                'quantity' => $request->quantity,
                'checked_by' => Auth::user()->id,
                'remarks' => 'added',

            );
            OutletCheckIn::create($data);
//            MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)->update([''])

            return redirect()->back()->with('success', ' Successfully Recieved This Product.');
        } catch (Exception $e) {
            return redirect()->route('distribute-medicine.index')->with('success', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OutletStock  $outletStock
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $warehouseStock = OutletStock::find($id);
        return view('admin.medicinestock.print_barcode', compact('warehouseStock'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OutletStock  $outletStock
     * @return \Illuminate\Http\Response
     */
    public function edit(OutletStock $outletStock)
    {
        return view('admin.medicinestock.edit_outlet_stock', compact('outletStock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OutletStock  $outletStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutletStock $outletStock)
    {
        $data = array(
            'purchase_price' => $request->purchase_price,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'size' => $request->size,
            'create_date' => $request->create_date,

        );

        OutletStock::where('id', $request->id)->where('medicine_id', $request->medicine_id)->update($data);
        return redirect()->back()->with('success', ' Successfully Product stock Update.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OutletStock  $outletStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutletStock $outletStock)
    {
        //
    }
    public function outletStock(Request $request, $id)
    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $row_per_page = $request->get("length"); // rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = (isset($columnIndex_arr[0]['column'])) ? $columnIndex_arr[0]['column'] : false; // column index
        $columnName = (isset($columnName_arr[$columnIndex]['data'])) ? $columnName_arr[$columnIndex]['data'] : false; // column name
        $columnSortOrder = (isset($order_arr[0]['dir'])) ? $order_arr[0]['dir'] : false; // asc or desc
        $searchValue = (isset($search_arr['value'])) ? $search_arr['value'] : false; // Search value

        // total records count
        if ($id == 'all') {

            $medicine_stock_query = DB::table('outlet_stocks')
                ->orderBy($columnName, $columnSortOrder)
                ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
                ->leftJoin('categories', 'medicines.category_id', '=', 'categories.id')
                ->Where('medicines.medicine_name', 'like', '%' . $searchValue . '%')
                ->orWhere('categories.category_name', 'like', '%' . $searchValue . '%')
                ->select('outlet_stocks.*', 'medicines.medicine_name');


        } else {
            if (auth()->user()->hasrole(['Super Admin', 'Admin'])) {
                $medicine_stock_query = DB::table('outlet_stocks')
                    ->orderBy($columnName, $columnSortOrder)
                    ->where('outlet_stocks.outlet_id', '=' , $id)
                    ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
                    ->Where('medicines.medicine_name', 'like', '%' . $searchValue . '%')
                    ->select('outlet_stocks.*', 'medicines.medicine_name');
            } else {
                $medicine_stock_query = DB::table('outlet_stocks')
                    ->orderBy($columnName, $columnSortOrder)
                    ->where('outlet_id', Auth::user()->outlet_id)
                    ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
                    ->Where('medicines.medicine_name', 'like', '%' . $searchValue . '%')
                    ->select('outlet_stocks.*', 'medicines.medicine_name');
            }

        }
        $totalRecords = $medicine_stock_query->count();
        $medicine_stock = $medicine_stock_query
            ->skip($start)
            ->take($row_per_page)
            ->get();
        $total_record_switch_filter = $totalRecords;

        // fetch records with search

        $data_arr = array();

        $sl = 1;
        $total = 0;

        foreach ($medicine_stock as $stock) {
            if ($stock->quantity < 1){

                continue;

            }else{

                $s_no = $sl++;
                $medicine_name = Medicine::get_medicine_name($stock->medicine_id);
                if (auth()->user()->hasrole(['Super Admin', 'Admin'])) {
                    $manufacturer_price = '৳&nbsp;' . $stock->purchase_price;
                }else{
                    $manufacturer_price = 'N/A';
                }
                $medicine = Medicine::where('id', $stock->medicine_id)->first();
                $category = Category::get_category_name($medicine->category_id);
                $manufacturer_name = Manufacturer::get_manufacturer_name($medicine->manufacturer_id);
                $price = '৳&nbsp;' . $stock->price;
                $size = $stock->size;

                $stocks = $stock->quantity;
                $ul = route('outlet-stock.edit', $stock->id);
                $barcode_url = route('outlet-stock.show', $stock->id);
                $url = '<a href="' . $ul . '"class="btn btn-success btn-xs" title="Edit" style="margin-right:3px"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                $url .= '<a href="' . $barcode_url . '"class="btn btn-primary btn-xs" title="Print Barcode" style="margin-right:3px" target="_blank"><i class="fa fa-barcode" aria-hidden="true"></i></a>';
                $total = $total + $stock->price * $stock->quantity;
                $data_arr[] = array(
                    "id" => $s_no,
                    "medicine_name" => $medicine_name,
                    "category" => $category,
                    "manufacturer_name" => $manufacturer_name,
                    "price" => $price,
                    "manufacturer_price" => $manufacturer_price,
                    "quantity" => $stocks,
                    "size" => $size,
                    "action" => $url,

                );

            }

        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $total_record_switch_filter,
            "aaData" => $data_arr,
            "total" => $total,
        );

        return response()->json($response);

    }

    public function outletStock2(Request $request, $id)
    {
        // total records count
        if ($id == 'all') {

            $medicine_stock = DB::table('outlet_stocks')->where('quantity', '>', '0')->leftjoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')->select('outlet_stocks.*', 'medicines.medicine_name')

                ->get();

        } else {
            if (auth()->user()->hasrole('Super Admin')) {

                $medicine_stock = DB::table('outlet_stocks')->where('outlet_id', '=', $id)->where('quantity', '>', '0')->leftjoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')->select('outlet_stocks.*', 'medicines.medicine_name')
                    ->get();

            } else {

                $medicine_stock = DB::table('outlet_stocks')->where('outlet_id', '=', Auth::user()->outlet_id)->where('quantity', '>', '0')->leftjoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')->select('outlet_stocks.*', 'medicines.medicine_name')
                    ->get();

            }

        }

        $total = 0;
        foreach ($medicine_stock as $stock) {

            $total = $total + $stock->purchase_price * $stock->quantity;

        }

        $response = array(

            "total" => $total,
        );

        return response()->json($response);

    }

    public function getoutletStocks(Request $request, $id)
    {

        $search = $request->search;
        if ($search == '') {

            $medicines = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $id)->where('outlet_stocks.quantity', '>', '0')
                ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
                ->select('outlet_stocks.medicine_id as id', 'medicines.category_id as category_id', 'outlet_stocks.size as size', 'medicines.medicine_name as medicine_name', 'medicines.id as medicine_id')->limit(20)->get();

        } else {

            $medicines = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $id)->where('outlet_stocks.quantity', '>', '0')
                ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
                ->select('outlet_stocks.medicine_id as id', 'medicines.category_id as category_id', 'outlet_stocks.size as size', 'medicines.medicine_name as medicine_name', 'medicines.id as medicine_id')->where('medicine_name', 'like', '%' . $search . '%')->get();

        }

        $response = array();
        foreach ($medicines as $medicine) {
            $category = Category::where('id', $medicine->category_id)->first();
            $response[] = array(
                "id" => $medicine->medicine_id . ',' . $medicine->size,
                "text" => $medicine->medicine_name . ' - ' . $category->category_name . ' - ' . '  ' . $medicine->size,
            );
        }
        return response()->json($response);
    }

    public function get_medicine_details_outlet($id, $id2)
    {
        $data = explode(",", $id);

        $product_details = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $id2)->where('outlet_stocks.medicine_id', '=', $data[0])->where('outlet_stocks.size', '=', $data[1])
            ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
            ->select('outlet_stocks.*', 'medicines.medicine_name as medicine_name')->first();

        // $product_details = Medicine::where('id', $id)->select('id','medicine_name','price','manufacturer_price')->first();
        return json_encode($product_details);
    }

    public function allInOne(Request $request){
        $datas = MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)->get();
        foreach($datas as $data1){

            $manu_price = WarehouseStock::where('warehouse_id', $request->warehouse_id)->where('medicine_id', $data1->medicine_id)->where('size', '=', $data1->size)->first();
            $data = array(
                'quantity' => (int) $data1->quantity,
                'price' => $data1->rate,
                'outlet_id' => $request->outlet_id,
                'medicine_id' => $data1->medicine_id,
                'size' => $data1->size,
                'create_date' => $data1->create_date,
                'barcode_text' => $data1->barcode,
                'warehouse_stock_id' => $data1->stock_id ?? null,
                'purchase_price' => $manu_price->purchase_price,

            );

            $check = OutletStock::where('outlet_id', $request->outlet_id)->where('medicine_id', $data1->medicine_id)->where('size', '=', $data1->size)->first();

            if ($check != null) {
                $stock2 = array(

                    'quantity' => (int) $check->quantity + (int) $data1->quantity,
                    'price' => $data1->rate,
                    'purchase_price' => $manu_price->purchase_price,
                );
                $has_received2 = array(

                    'has_received' => '1',

                );
                MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)->where('medicine_id', $data1->medicine_id)->where('size', '=', $data1->size)->where('create_date', '=', $data1->create_date)->update($has_received2);
                OutletStock::where('outlet_id', $request->outlet_id)->where('medicine_id', $data1->medicine_id)->where('size', '=', $data1->size)->update($stock2);

            } else {
                $has_received2 = array(

                    'has_received' => '1',

                );
                MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)->where('medicine_id', $data1->medicine_id)->where('size', '=', $data1->size)->where('create_date', '=', $data1->create_date)->update($has_received2);
                $check = OutletStock::create($data);

            }
            $check2 = MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)->where('has_received', '0')->get();
            if (count($check2) < 1) {
                $has_received = array(
                    'has_received' => '1',

                );
                MedicineDistribute::where('id', $request->medicine_distribute_id)->update($has_received);
            }

                $data3 = array(
                    'outlet_id' => $request->outlet_id,
                    'medicine_distribute_id' => $request->medicine_distribute_id,
                    'medicine_id' => $data1->medicine_id,
                    'size' => $data1->size,
                    'create_date' => $data1->create_date,
                    'quantity' => $data1->quantity,
                    'checked_by' => Auth::user()->id,
                    'remarks' => 'added',

                );
                OutletCheckIn::create($data3);
    //            MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)->update([''])

        }
        return redirect()->back()->with('success', ' Successfully Received All This Product.');
    }
}
