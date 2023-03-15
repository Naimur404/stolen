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
use App\Models\Unit;
use App\Models\WarehouseStock;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OutletStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
         $this->middleware('permission:outletStock', ['only' => ['outletStock']]);

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



        $manu_price = WarehouseStock::where('warehouse_id',$request->warehouse_id)->where('medicine_id',$request->medicine_id)->whereDate('expiry_date','=',$request->expiry_date)->first();
        $data = array(
            'quantity' => (int)$request->quantity,
            'price'  => $request->price,
            'outlet_id' => $request->outlet_id,
            'medicine_id' => $request->medicine_id,
            'expiry_date' => $request->expiry_date,
            'purchase_price' => $manu_price->purchase_price,
            'price'  => $request->price,

        );



        $check = OutletStock::where('outlet_id', $request->outlet_id)->where('medicine_id',$request->medicine_id)->whereDate('expiry_date','=',$request->expiry_date)->first();


        if($check != null){
            $stock2 = array(

                'quantity' => (int)$check->quantity + (int)$request->quantity,
                'price'  => $request->price,
                'purchase_price' => $manu_price->purchase_price,
            );
            $has_received2 = array(

                'has_received' => '1',

            );
              MedicineDistributeDetail::where('medicine_distribute_id',$request->medicine_distribute_id)->where('medicine_id',$request->medicine_id)->whereDate('expiry_date','=',$request->expiry_date)->update($has_received2);
               OutletStock::where('outlet_id', $request->outlet_id)->where('medicine_id',$request->medicine_id)->whereDate('expiry_date','=',$request->expiry_date)->update($stock2);

        }else{
            $has_received2 = array(

                'has_received' => '1',

            );
           MedicineDistributeDetail::where('medicine_distribute_id',$request->medicine_distribute_id)->where('medicine_id',$request->medicine_id)->whereDate('expiry_date','=',$request->expiry_date)->update($has_received2);
            $check =  OutletStock::create($data);


        }
        $check2 =  MedicineDistributeDetail::where('medicine_distribute_id',$request->medicine_distribute_id)->where('has_received','0')->get();
        if(count($check2) < 1){
            $has_received = array(
                'has_received' => '1',

          );
          MedicineDistribute::where('id',$request->medicine_distribute_id)->update($has_received);
        }





          try{
            $data = array(
              'outlet_id'    => $request->outlet_id,
              'medicine_distribute_id'    => $request->medicine_distribute_id,
              'medicine_id'    => $request->medicine_id,
              'expiry_date'    => $request->expiry_date,
              'quantity'    => $request->quantity,
              'checked_by' => Auth::user()->id,
              'remarks'    => 'added',

            );
            OutletCheckIn::create($data);
//            MedicineDistributeDetail::where('medicine_distribute_id', $request->medicine_distribute_id)->update([''])

           return redirect()->back()->with('success', ' Successfully Recieved This Medicine.');
        }catch(Exception $e){
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OutletStock  $outletStock
     * @return \Illuminate\Http\Response
     */
    public function edit(OutletStock $outletStock)
    {
        //
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
        //
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
    public function outletStock(Request $request, $id )

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


            $totalRecords = OutletStock::where('quantity', '>', '0')->select('count(*) as allcount')
            ->count();
           $medicine_stock  = DB::table('outlet_stocks')->orderBy($columnName,$columnSortOrder)->where('quantity', '>', '0')->leftjoin('medicines' ,'outlet_stocks.medicine_id' ,'=' ,'medicines.id')->where('medicines.medicine_name', 'like', '%' .$searchValue . '%')->select('outlet_stocks.*', 'medicines.medicine_name')

              ->skip($start)
              ->take($row_per_page)
              ->get();

        }else{
           if(auth()->user()->hasrole('Super Admin')){
            $totalRecords = OutletStock::where('quantity', '>', '0')->select('count(*) as allcount')->where('outlet_id', '=', $id)->count();
            $medicine_stock =    DB::table('outlet_stocks')->orderBy($columnName,$columnSortOrder)->where('outlet_id', '=', $id)->where('quantity', '>', '0')->leftjoin('medicines' ,'outlet_stocks.medicine_id' ,'=' ,'medicines.id')->where('medicines.medicine_name', 'like', '%' .$searchValue . '%')->select('outlet_stocks.*', 'medicines.medicine_name')

              ->skip($start)
              ->take($row_per_page)
              ->get();

           }else{
            $totalRecords = OutletStock::where('quantity', '>', '0')->select('count(*) as allcount')->where('outlet_id', '=', Auth::user()->outlet_id)->count();
            $medicine_stock =    DB::table('outlet_stocks')->orderBy($columnName,$columnSortOrder)->where('outlet_id', '=', Auth::user()->outlet_id)->where('quantity', '>', '0')->leftjoin('medicines' ,'outlet_stocks.medicine_id' ,'=' ,'medicines.id')->where('medicines.medicine_name', 'like', '%' .$searchValue . '%')->select('outlet_stocks.*', 'medicines.medicine_name')

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
            $manufacturer_price = '৳&nbsp;' .$stock->purchase_price;
            $medicine = Medicine::where('id',$stock->medicine_id)->first();
            $category = Category::get_category_name($medicine->category_id);
            $manufacturer_name = Manufacturer::get_manufacturer_name($medicine->manufacturer_id);
            $price = '৳&nbsp;'.$stock->price;
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

    public function getoutletStocks(Request $request,$id){


        $search = $request->search;
        if($search == ''){

            $medicines = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $id)->where('outlet_stocks.quantity' ,'>' ,'0')
            ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
            ->select('outlet_stocks.medicine_id as id','medicines.category_id as category_id','outlet_stocks.expiry_date as expiry_date','medicines.medicine_name as medicine_name','medicines.id as medicine_id')->limit(20)->get();

         }else{

            $medicines = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $id )->where('outlet_stocks.quantity' ,'>' ,'0')
            ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
            ->select('outlet_stocks.medicine_id as id','medicines.category_id as category_id','outlet_stocks.expiry_date as expiry_date','medicines.medicine_name as medicine_name' ,'medicines.id as medicine_id')->where('medicine_name', 'like', '%' .$search . '%')->get();



         }

         $response = array();
         foreach($medicines as $medicine){
            $category = Category::where('id',$medicine->category_id)->first();
           $response[] = array(
                "id"=>$medicine->medicine_id.','.$medicine->expiry_date,
                "text"=>$medicine->medicine_name.' - '.$category->category_name.' - '.' EX '.$medicine->expiry_date,
           );
        }
         return response()->json($response);
      }

      public function get_medicine_details_outlet($id,$id2){
        $data = explode(",", $id);

        $product_details = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $id2 )->where('outlet_stocks.medicine_id' ,'=' , $data[0])->whereDate('outlet_stocks.expiry_date','=',$data[1])
        ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
        ->select('outlet_stocks.*','medicines.medicine_name as medicine_name')->first();

        // $product_details = Medicine::where('id', $id)->select('id','medicine_name','price','manufacturer_price')->first();
        return json_encode($product_details);
  }
}
