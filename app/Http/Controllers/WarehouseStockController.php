<?php

namespace App\Http\Controllers;
use App\Models\BarcodeLog;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Medicine;
use App\Models\MedicineDistribute;
use App\Models\MedicineDistributeDetail;
use App\Models\Warehouse;
use App\Models\WarehouseCheckIn;
use App\Models\WarehouseStock;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:warehouseStock', ['only' => ['warehouseStock']]);
        $this->middleware('permission:warehousetStock-price-edit', ['only' => ['edit']]);
        $this->middleware('permission:warehouseStock-price-update', ['only' => ['warehouse_Stock_Update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasrole('Super Admin')) {
            $warehouse = Warehouse::pluck('warehouse_name', 'id');

            $warehouse = new Collection($warehouse);
            $warehouse->prepend('All Warehouse Stock', 'all');
        } else {
            $warehouse = Warehouse::where('id', Auth::user()->warehouse_id)->pluck('warehouse_name', 'id');
        }

        return view('admin.medicinestock.warehousestock', compact('warehouse'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();

        $warehousecheck = WarehouseStock::where('warehouse_id', $input['warehouse_id'])->where('medicine_id', $input['medicine_id'])->where('size', '=', $input['size'])->first();

        if ($warehousecheck != null) {
            $quantity = array(
                'quantity' => (int) $input['quantity'] + (int) $warehousecheck->quantity,
            );
            WarehouseStock::where('warehouse_id', $input['warehouse_id'])->where('medicine_id', $input['medicine_id'])->where('size', '=', $input['size'])->update($quantity);
        } else {

            $warehouseStock = WarehouseStock::create($input);
            $barcode = BarcodeLog::generateBarcodeText();
            // set the barcode text to the generated value
            $warehouseStock->barcode_text = $barcode;
            $warehouseStock->save();
        }

        try {
            $data = array(
                'warehouse_id' => $request->warehouse_id,
                'purchase_id' => $request->purchase_id,
                'medicine_id' => $request->medicine_id,
                'size' => $request->size,
                'quantity' => $request->quantity,
                'create_date' => $request->create_date,
                'checked_by' => Auth::user()->id,

            );
            WarehouseCheckIn::create($data);

            return redirect()->back()->with('success', ' Successfully Added.');
        } catch (Exception $e) {
            return redirect()->route('medicine-purchase.index')->with('success', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WarehouseStock  $warehouseStock
     * @return \Illuminate\Http\Response
     */
    public function show(WarehouseStock $warehouseStock)
    {
        return view('admin.medicinestock.print_barcode', compact('warehouseStock'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WarehouseStock  $warehouseStock
     * @return \Illuminate\Http\Response
     */
    public function edit(WarehouseStock $warehouseStock)
    {
        return view('admin.medicinestock.edit_warehouse_stock', compact('warehouseStock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WarehouseStock  $warehouseStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        dump($request->all());

        // try {
        //     $warehousetock = WarehouseStock::where('warehouse_id', $id)->where('medicine_id', $request->medicine_id)->where('size', '=', $request->size)->first();

        //     if ($warehousetock != null) {
        //         $new_stock = array(
        //             'quantity' => (int) $warehousetock->quantity - (int) $request->quantity,

        //         );
        //         $has_received2 = array(

        //             'has_sent' => '1',

        //         );
        //         MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)->where('medicine_id', $request->medicine_id)->where('size', '=', $request->size)->where('create_date', '=', $request->create_date)->update($has_received2);

        //         WarehouseStock::where('warehouse_id', $request->warehouse_id)->where('medicine_id', $request->medicine_id)->where('size', '=', $request->size)->update($new_stock);
        //     }
        //     $check = MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)->where('has_sent', '0')->where('create_date', '=', $request->create_date)->get();
        //     if (count($check) < 1) {
        //         $has_received = array(
        //             'has_sent' => '1',
        //         );
        //         MedicineDistribute::where('id', $request->medicine_distribute_id)->update($has_received);
        //     }

        //     return redirect()->back()->with('success', ' Successfully Distribute This Product.');
        // } catch (Exception $e) {
        //     return redirect()->route('distribute-medicine.index')->with('success', $e->getMessage());
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WarehouseStock  $warehouseStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(WarehouseStock $warehouseStock)
    {
        //
    }
    public function warehouseStock(Request $request, $id)
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
            $totalRecords = WarehouseStock::where('quantity', '>', '0')->select('count(*) as allcount')
                ->count();
            $medicine_stock = DB::table('warehouse_stocks')->orderBy($columnName, $columnSortOrder)->where('quantity', '>', '0')->leftjoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')->where('medicines.medicine_name', 'like', '%' . $searchValue . '%')->select('warehouse_stocks.*', 'medicines.medicine_name')
                ->skip($start)
                ->take($row_per_page)
                ->get();
        } else {
            if (auth()->user()->hasrole('Super Admin')) {
                $totalRecords = WarehouseStock::where('quantity', '>', '0')->select('count(*) as allcount')->where('warehouse_id', '=', $id)->count();
                $medicine_stock = DB::table('warehouse_stocks')->orderBy($columnName, $columnSortOrder)->where('warehouse_id', '=', $id)->where('quantity', '>', '0')->leftjoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')->where('medicines.medicine_name', 'like', '%' . $searchValue . '%')->select('warehouse_stocks.*', 'medicines.medicine_name')
                    ->skip($start)
                    ->take($row_per_page)
                    ->get();
            } else {
                $totalRecords = WarehouseStock::where('quantity', '>', '0')->select('count(*) as allcount')->where('warehouse_id', '=', Auth::user()->warehouse_id)->count();
                $medicine_stock = DB::table('warehouse_stocks')->orderBy($columnName, $columnSortOrder)->where('warehouse_id', '=', Auth::user()->warehouse_id)->where('quantity', '>', '0')->leftjoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')->where('medicines.medicine_name', 'like', '%' . $searchValue . '%')->select('warehouse_stocks.*', 'medicines.medicine_name')
                    ->skip($start)
                    ->take($row_per_page)
                    ->get();
            }
        }

        $total_record_switch_filter = $totalRecords;
        $data_arr = array();

        $sl = 1;

        foreach ($medicine_stock as $stock) {
            $s_no = $sl++;
            $medicine_name = Medicine::get_medicine_name($stock->medicine_id);
            $manufacturer_price = '৳&nbsp;' . $stock->purchase_price;
            $medicine = Medicine::where('id', $stock->medicine_id)->first();
            $category = Category::get_category_name($medicine->category_id);
            $manufacturer_name = Manufacturer::get_manufacturer_name($medicine->manufacturer_id);
            $price = '৳&nbsp' . $stock->price;
            $size = $stock->size;

            $stocks = $stock->quantity;
            $ul = route('warehouse-stock.edit', $stock->id);
            $barcode_url = route('warehouse-stock.show', $stock->id);
            $url = '<a href="' . $ul . '"class="btn btn-success btn-xs" title="Edit" style="margin-right:3px"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
            $url .= '<a href="' . $barcode_url . '"class="btn btn-primary btn-xs" title="Print Barcode" style="margin-right:3px" target="_blank"><i class="fa fa-barcode" aria-hidden="true"></i></a>';

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

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $total_record_switch_filter,
            "aaData" => $data_arr,
        );

        return json_encode($response);
    }

    public function warehouseStock2(Request $request, $id)
    {
        // total records count
        if ($id == 'all') {

            $medicine_stock = DB::table('warehouse_stocks')->where('quantity', '>', '0')->leftjoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')->select('warehouse_stocks.*', 'medicines.medicine_name')
                ->get();
        } else {
            if (auth()->user()->hasrole('Super Admin')) {

                $medicine_stock = DB::table('warehouse_stocks')->where('warehouse_id', '=', $id)->where('quantity', '>', '0')->leftjoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')->select('warehouse_stocks.*', 'medicines.medicine_name')
                    ->get();
            } else {

                $medicine_stock = DB::table('warehouse_stocks')->where('warehouse_id', '=', Auth::user()->warehouse_id)->where('quantity', '>', '0')->leftjoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')->select('warehouse_stocks.*', 'medicines.medicine_name')
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

    public function getwarehouseStock(Request $request, $id)
    {

        $search = $request->search;
        if ($search == '') {

            $medicines = DB::table('warehouse_stocks')->where('warehouse_stocks.warehouse_id', $id)->where('warehouse_stocks.quantity', '>', '0')
                ->leftJoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')
                ->select('warehouse_stocks.medicine_id as id', 'medicines.category_id as category_id', 'warehouse_stocks.size as size', 'medicines.medicine_name as medicine_name', 'medicines.id as medicine_id')->limit(20)->get();
        } else {

            $medicines = DB::table('warehouse_stocks')->where('warehouse_stocks.warehouse_id', $id)->where('warehouse_stocks.quantity', '>', '0')
                ->leftJoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')
                ->select('warehouse_stocks.medicine_id as id', 'medicines.category_id as category_id', 'warehouse_stocks.size as size', 'medicines.medicine_name as medicine_name', 'medicines.id as medicine_id')->where('medicine_name', 'like', '%' . $search . '%')->get();
        }

        $response = array();
        foreach ($medicines as $medicine) {
            $category = Category::where('id', $medicine->category_id)->first();
            $response[] = array(
                "id" => $medicine->medicine_id . ',' . $medicine->size,
                "text" => $medicine->medicine_name . ' - ' . $category->category_name . ' - ' . ' ' . $medicine->size,
            );
        }

        return response()->json($response);
    }

    public function get_medicine_details_warehouse($id, $id2)
    {
        $data = explode(",", $id);

        $product_details = DB::table('warehouse_stocks')->where('warehouse_stocks.warehouse_id', $id2)->where('warehouse_stocks.medicine_id', '=', $data[0])->where('warehouse_stocks.size', '=', $data[1])
            ->leftJoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')
            ->select('warehouse_stocks.*', 'medicines.medicine_name as medicine_name')->first();

        // $product_details = Medicine::where('id', $id)->select('id','medicine_name','price','manufacturer_price')->first();
        return json_encode($product_details);
    }

    public function warehouse_Stock_Update(Request $request)
    {

        $data = array(
            'purchase_price' => $request->purchase_price,
            'price' => $request->price,
            'size' =>$request->size,
            'quantity' => $request->quantity,
        );

        WarehouseStock::where('id', $request->id)->where('medicine_id', $request->medicine_id)->update($data);
        return redirect()->back()->with('success', ' Successfully Medicine Price Update.');
    }

    public function allInOne(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'disid' => 'required|integer|exists:medicine_distributes,id',
            'warehouse_id' => 'required|integer|exists:warehouses,id'
        ]);
    
        try {
            // Fetch all distribution details at once
            $distributeDetails = MedicineDistributeDetail::where('medicine_distribute_id', $request->disid)
                ->where('has_sent', '0') // Only process unsent items
                ->get();
    
            if ($distributeDetails->isEmpty()) {
                return redirect()->back()->with('info', 'No items available to distribute.');
            }
    
            // Begin database transaction
            DB::beginTransaction();
    
            foreach ($distributeDetails as $detail) {
                // Find warehouse stock
                $warehouseStock = WarehouseStock::where('warehouse_id', $request->warehouse_id)
                    ->where('medicine_id', $detail->medicine_id)
                    ->where('size', $detail->size)
                    ->lockForUpdate() // Prevent race conditions
                    ->first();
    
                // Check if stock exists and has sufficient quantity
                if (!$warehouseStock) {
                    throw new Exception("Stock not found for medicine ID: {$detail->medicine_id}");
                }
    
                $newQuantity = (int)$warehouseStock->quantity - (int)$detail->quantity;
                if ($newQuantity < 0) {
                    throw new Exception("Insufficient stock for medicine ID: {$detail->medicine_id}");
                }
    
                // Update warehouse stock
                $warehouseStock->update([
                    'quantity' => $newQuantity,
                    'updated_at' => now()
                ]);
    
                // Update distribution detail
                $detail->update([
                    'has_sent' => '1'
                ]);
            }
    
            // Check if all items in distribution are sent
            $pendingItems = MedicineDistributeDetail::where('medicine_distribute_id', $request->disid)
                ->where('has_sent', '0')
                ->count();
    
            if ($pendingItems === 0) {
                MedicineDistribute::where('id', $request->disid)
                    ->update([
                        'has_sent' => '1'
                    ]);
            }
    
            DB::commit();
            
            return redirect()->back()->with('success', 'Successfully distributed all products.');
    
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Distribution failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to distribute products: ' . $e->getMessage())
                ->withInput();
        }
    }
}
