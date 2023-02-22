<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Medicine;
use App\Models\MedicineDistribute;
use App\Models\MedicineDistributeDetail;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WarehouseCheckIn;
use App\Models\WarehouseStock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class WarehouseStockController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:warehouseStock', ['only' => ['warehouseStock']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { if(auth()->user()->hasrole('Super Admin')){
        $warehouse = Warehouse::pluck('warehouse_name', 'id');

        $warehouse = new Collection($warehouse);
        $warehouse->prepend('All Warehouse Stock', 'all');
    }else{
        $warehouse = Warehouse::where('id',Auth::user()->warehouse_id)->pluck('warehouse_name', 'id');
    }



          return view('admin.medicinestock.warehousestock',compact('warehouse'));
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

        $warehousecheck = WarehouseStock::where('warehouse_id', $input['warehouse_id'])->where('medicine_id',$input['medicine_id'])->whereDate('expiry_date','=',$input['expiry_date'])->first();

        if($warehousecheck != null){
            $quantity = array(
                'quantity' => (int)$input['quantity'] +  (int)$warehousecheck->quantity,
            );
            WarehouseStock::where('warehouse_id', $input['warehouse_id'])->where('medicine_id',$input['medicine_id'])->whereDate('expiry_date','=',$input['expiry_date'])->update($quantity);
        }else{
             WarehouseStock::create($input);
        }

        try{
          $data = array(
            'warehouse_id'    => $request->warehouse_id,
            'purchase_id'    => $request->purchase_id,
            'medicine_id'    => $request->medicine_id,
            'expiry_date'    => $request->expiry_date,
            'quantity'    => $request->quantity,
             'checked_by' => Auth::user()->id,


          );
            WarehouseCheckIn::create($data);

         return redirect()->back()->with('success', ' Successfully Added.');
      }catch(Exception $e){
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WarehouseStock  $warehouseStock
     * @return \Illuminate\Http\Response
     */
    public function edit(WarehouseStock $warehouseStock)
    {
        //
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


             try{
                 $warehousetock = WarehouseStock::where('warehouse_id', $id)->where('medicine_id',$request->medicine_id)->whereDate('expiry_date','=',$request->expiry_date)->first();

           if( $warehousetock != null){
            $new_stock = array(
                'quantity' => (int)$warehousetock->quantity - (int)$request->quantity,

          );
          $has_received2 = array(

            'has_sent' => '1',

        );
        MedicineDistributeDetail::where('medicine_distribute_id',$request->medicine_distribute_id)->where('medicine_id',$request->medicine_id)->whereDate('expiry_date','=',$request->expiry_date)->update($has_received2);

          WarehouseStock::where('warehouse_id', $request->warehouse_id)->where('medicine_id',$request->medicine_id)->whereDate('expiry_date','=',$request->expiry_date)->update($new_stock);
        }


            $has_received = array(
                'has_sent' => '1',
            );

          MedicineDistribute::where('id',$request->medicine_distribute_id)->update($has_received);


        return redirect()->back()->with('success', ' Successfully Distriute This Medicine.');
    }

    catch(Exception $e){
       return redirect()->route('distribute-medicine.index')->with('success', $e->getMessage());
    }

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
    public function warehouseStock(Request $request, $id )

    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $row_per_page = $request->get("length"); // rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = (isset($columnIndex_arr[0]['column']))? $columnIndex_arr[0]['column'] : false; // column index
        $columnName = (isset($columnName_arr[$columnIndex]['data']))? $columnName_arr[$columnIndex]['data'] : false; // column name
        $columnSortOrder = (isset($order_arr[0]['dir']))? $order_arr[0]['dir'] : false; // asc or desc
        $searchValue = (isset($search_arr['value'])) ? $search_arr['value'] : false; // Search value



        // total records count
        if($id == 'all'){


            $totalRecords = WarehouseStock::where('quantity', '>', '0')->select('count(*) as allcount')
            ->count();
           $medicine_stock  = DB::table('warehouse_stocks')->orderBy($columnName,$columnSortOrder)->where('quantity', '>', '0')->leftjoin('medicines' ,'warehouse_stocks.medicine_id' ,'=' ,'medicines.id')->where('medicines.medicine_name', 'like', '%' .$searchValue . '%')->select('warehouse_stocks.*', 'medicines.medicine_name')

              ->skip($start)
              ->take($row_per_page)
              ->get();

        }else{
           if(auth()->user()->hasrole('Super Admin')){
            $totalRecords = WarehouseStock::where('quantity', '>', '0')->select('count(*) as allcount')->where('warehouse_id', '=', $id)->count();
            $medicine_stock =    DB::table('warehouse_stocks')->orderBy($columnName,$columnSortOrder)->where('warehouse_id', '=', $id)->where('quantity', '>', '0')->leftjoin('medicines' ,'warehouse_stocks.medicine_id' ,'=' ,'medicines.id')->where('medicines.medicine_name', 'like', '%' .$searchValue . '%')->select('warehouse_stocks.*', 'medicines.medicine_name')

              ->skip($start)
              ->take($row_per_page)
              ->get();

           }else{
            $totalRecords = WarehouseStock::where('quantity', '>', '0')->select('count(*) as allcount')->where('warehouse_id', '=', Auth::user()->warehouse_id)->count();
            $medicine_stock =    DB::table('warehouse_stocks')->orderBy($columnName,$columnSortOrder)->where('warehouse_id', '=', Auth::user()->warehouse_id)->where('quantity', '>', '0')->leftjoin('medicines' ,'warehouse_stocks.medicine_id' ,'=' ,'medicines.id')->where('medicines.medicine_name', 'like', '%' .$searchValue . '%')->select('warehouse_stocks.*', 'medicines.medicine_name')

            ->skip($start)
            ->take($row_per_page)
            ->get();


           }





        }

        $total_record_switch_filter = $totalRecords;

        // fetch records with search




        $data_arr = array();

        $sl = 1;

        foreach($medicine_stock as $stock){
            $s_no = $sl++;
            $medicine_name = Medicine::get_medicine_name($stock->medicine_id);
            $manufacturer_price = '৳&nbsp;' .Medicine::get_manufacturer_price($stock->medicine_id);;
             $medicine = Medicine::where('id',$stock->medicine_id)->first();
            $category = Category::get_category_name($medicine->category_id);
            $manufacturer_name = Manufacturer::get_manufacturer_name($medicine->manufacturer_id);
            $price = '৳&nbsp'.$stock->price;
            $expiry_date = $stock->expiry_date;

            $stock = $stock->quantity;

            $data_arr[] = array(
              "id" => $s_no,
              "medicine_name" => $medicine_name,

              "category" => $category,
              "manufacturer_name" => $manufacturer_name,
              "price" => $price,
              "manufacturer_price" => $manufacturer_price,
              "quantity" => $stock,
              "expiry_date" => $expiry_date,
            );
         }

         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $total_record_switch_filter,
            "aaData" => $data_arr
         );

         return json_encode($response);

    }



    public function getwarehouseStock(Request $request,$id){


        $search = $request->search;
        if($search == ''){

            $medicines = DB::table('warehouse_stocks')->where('warehouse_stocks.warehouse_id', $id )->where('warehouse_stocks.quantity' ,'>' ,'0')
            ->leftJoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')
            ->select('warehouse_stocks.medicine_id as id','warehouse_stocks.expiry_date as expiry_date','medicines.medicine_name as medicine_name','medicines.id as medicine_id')->limit(20)->get();

         }else{

            $medicines = DB::table('warehouse_stocks')->where('warehouse_stocks.warehouse_id', $id )->where('warehouse_stocks.quantity' ,'>' ,'0')
            ->leftJoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')
            ->select('warehouse_stocks.medicine_id as id','warehouse_stocks.expiry_date as expiry_date','medicines.medicine_name as medicine_name' ,'medicines.id as medicine_id')->where('medicine_name', 'like', '%' .$search . '%')->get();



         }

         $response = array();
         foreach($medicines as $medicine){
            $response[] = array(
                 "id"=>$medicine->medicine_id,
                 "text"=>$medicine->medicine_name.' - '.' EX '.$medicine->expiry_date,
            );
         }
         return response()->json($response);
      }

      public function get_medicine_details_warehouse($id,$id2){


        $product_details = DB::table('warehouse_stocks')->where('warehouse_stocks.warehouse_id', $id2 )->where('warehouse_stocks.medicine_id' ,'=' , $id)
        ->leftJoin('medicines', 'warehouse_stocks.medicine_id', '=', 'medicines.id')
        ->select('warehouse_stocks.*','medicines.medicine_name as medicine_name')->first();

        // $product_details = Medicine::where('id', $id)->select('id','medicine_name','price','manufacturer_price')->first();
        return json_encode($product_details);
  }
}
