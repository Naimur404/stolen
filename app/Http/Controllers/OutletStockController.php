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
use App\Models\BarcodeLog;

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

        $manuPrice = WarehouseStock::where('warehouse_id', $request->warehouse_id)
            ->where('medicine_id', $request->medicine_id)
            ->where('size', $request->size)
            ->first();
    
        $stockData = [
            'quantity' => (int) $request->quantity,
            'price' => $request->price,
            'outlet_id' => $request->outlet_id,
            'medicine_id' => $request->medicine_id,
            'size' => $request->size,
            'create_date' => $request->create_date,
            'barcode_text' => $request->barcode,
            'warehouse_stock_id' => $request->stock_id ?? null,
            'purchase_price' => $manuPrice->purchase_price,
        ];

    
    
        $existingStock = OutletStock::where('outlet_id', $request->outlet_id)
            ->where('medicine_id', $request->medicine_id)
            ->where('size', $request->size)
            ->first();
    
        $receivedUpdate = ['has_received' => '1'];
    
        if ($existingStock) {
            $updatedStock = [
                'quantity' => (int) $existingStock->quantity + (int) $request->quantity,
                'price' => $request->price,
                'purchase_price' => $manuPrice->purchase_price,
                'barcode_text' => $request->barcode,
            ];
    
            MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)
                ->where('medicine_id', $request->medicine_id)
                ->where('size', $request->size)
                ->where('create_date', $request->create_date)
                ->update($receivedUpdate);
    
            OutletStock::where('outlet_id', $request->outlet_id)
                ->where('medicine_id', $request->medicine_id)
                ->where('size', $request->size)
                ->update($updatedStock);
        } else {
            MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)
                ->where('medicine_id', $request->medicine_id)
                ->where('size', $request->size)
                ->where('create_date', $request->create_date)
                ->update($receivedUpdate);
    
            OutletStock::create($stockData);
        }
    
        $pendingItems = MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)
            ->where('has_received', '0')
            ->get();
    
        if ($pendingItems->isEmpty()) {
            MedicineDistribute::where('id', $request->medicine_distribute_id)
                ->update($receivedUpdate);
        }
    
        try {
            $checkInData = [
                'outlet_id' => $request->outlet_id,
                'medicine_distribute_id' => $request->medicine_distribute_id,
                'medicine_id' => $request->medicine_id,
                'size' => $request->size,
                'create_date' => $request->create_date,
                'quantity' => $request->quantity,
                'checked_by' => Auth::user()->id,
                'remarks' => 'added',
            ];
    
            OutletCheckIn::create($checkInData);
    
            return redirect()->back()->with('success', 'Product successfully received.');
        } catch (Exception $e) {
            return redirect()->route('distribute-medicine.index')
                ->with('success', $e->getMessage());
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
        $stock = OutletStock::find($id);
        return view('admin.medicinestock.print_barcode', compact('stock'));
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
        $searchParams = $this->getSearchParameters($request);
        
        // Build the base query with all necessary joins and conditions
        $query = $this->buildBaseQuery($id, $searchParams);
        
        // Get the total records before applying quantity filter
        $totalRecords = $query->count();
        
        // Apply quantity filter and get filtered records
        $filteredQuery = clone $query;
        $filteredQuery->whereRaw('outlet_stocks.quantity >= 1');
        
        // Get the total filtered records
        $totalFilteredRecords = $filteredQuery->count();
        
        // Get paginated results
        $stocks = $filteredQuery
            ->skip($searchParams['start'])
            ->take($searchParams['rowsPerPage'])
            ->get();
        
        $formattedData = $this->formatStockData($stocks);
        
        return response()->json([
            'draw' => intval($searchParams['draw']),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalFilteredRecords,
            'aaData' => $formattedData['data'],
            'total' => $formattedData['total']
        ]);
    }
    
    private function getSearchParameters(Request $request)
    {
        $columnIndex = $request->get('order')[0]['column'] ?? false;
        
        return [
            'draw' => $request->get('draw'),
            'start' => $request->get('start'),
            'rowsPerPage' => $request->get('length'),
            'columnName' => $request->get('columns')[$columnIndex]['data'] ?? 'id',
            'sortOrder' => $request->get('order')[0]['dir'] ?? 'asc',
            'searchValue' => $request->get('search')['value'] ?? ''
        ];
    }
    
    private function buildBaseQuery($id, array $params)
    {
        $baseQuery = DB::table('outlet_stocks')
            ->orderBy($params['columnName'], $params['sortOrder'])
            ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id');
    
        if ($id === 'all') {
            $baseQuery->leftJoin('categories', 'medicines.category_id', '=', 'categories.id')
                ->where(function($q) use ($params) {
                    $q->where('medicines.medicine_name', 'like', '%' . $params['searchValue'] . '%')
                      ->orWhere('categories.category_name', 'like', '%' . $params['searchValue'] . '%');
                });
        } else {
            if (auth()->user()->hasRole(['Super Admin', 'Admin'])) {
                $baseQuery->where('outlet_stocks.outlet_id', $id);
            } else {
                $baseQuery->where('outlet_stocks.outlet_id', auth()->user()->outlet_id);
            }
            
            if (!empty($params['searchValue'])) {
                $baseQuery->where('medicines.medicine_name', 'like', '%' . $params['searchValue'] . '%');
            }
        }
    
        return $baseQuery->select('outlet_stocks.*', 'medicines.medicine_name', 'medicines.category_id', 'medicines.manufacturer_id');
    }
    
    private function formatStockData($stocks)
    {
        $formattedData = ['data' => [], 'total' => 0];
        $serialNumber = 1;
    
        foreach ($stocks as $stock) {
            $formattedData['data'][] = [
                'id' => $serialNumber++,
                'medicine_name' => Medicine::get_medicine_name($stock->medicine_id),
                'category' => Category::get_category_name($stock->category_id),
                'manufacturer_name' => Manufacturer::get_manufacturer_name($stock->manufacturer_id),
                'price' => '৳ ' . $stock->price,
                'manufacturer_price' => $this->formatManufacturerPrice($stock->purchase_price),
                'quantity' => $stock->quantity,
                'size' => $stock->size,
                'action' => $this->generateActionButtons($stock->id)
            ];
    
            $formattedData['total'] += $stock->price * $stock->quantity;
        }
    
        return $formattedData;
    }
    
    private function formatManufacturerPrice($price)
    {
        if (auth()->user()->hasRole(['Super Admin', 'Admin'])) {
            return '৳ ' . $price;
        }
        return 'N/A';
    }
    
    private function generateActionButtons($stockId)
    {
        $editUrl = route('outlet-stock.edit', $stockId);
        $barcodeUrl = route('outlet-stock.show', $stockId);
        
        return sprintf(
            '<a href="%s" class="btn btn-success btn-xs" title="Edit" style="margin-right:3px">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </a>
            <a href="%s" class="btn btn-primary btn-xs" title="Print Barcode" style="margin-right:3px" target="_blank">
                <i class="fa fa-barcode" aria-hidden="true"></i>
            </a>',
            $editUrl,
            $barcodeUrl
        );
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
                'warehouse_id' => $request->warehouse_id,
                'outlet_id' => $request->outlet_id,
                'medicine_id' => $data1->medicine_id,
                'size' => $data1->size,
                'create_date' => $data1->create_date,
                'barcode_text' => $data1->warehouseStock->barcode_text,
                'warehouse_stock_id' => $data1->stock_id ?? null,
                'purchase_price' => $manu_price->purchase_price,

            );

            $check = OutletStock::where('outlet_id', $request->outlet_id)->where('medicine_id', $data1->medicine_id)->where('size', '=', $data1->size)->first();

            if ($check != null) {
                $stock2 = array(

                    'quantity' => (int) $check->quantity + (int) $data1->quantity,
                    'price' => $data1->rate,
                    'purchase_price' => $manu_price->purchase_price,
                    'barcode_text' => $data1->warehouseStock->barcode_text,
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
