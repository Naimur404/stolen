<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Medicine;
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

           $warehousetock = WarehouseStock::where('warehouse_id', $request->warehouse_id)->where('medicine_id',$request->medicine_id)->whereDate('expiry_date','=',$request->expiry_date)->first();

           if( $warehousetock != null){
            $new_stock = array(
                'quantity' => (int)$warehousetock->quantity - (int)$request->quantity,

          );

          WarehouseStock::where('warehouse_id', $request->warehouse_id)->where('medicine_id',$request->medicine_id)->whereDate('expiry_date','=',$request->expiry_date)->update($new_stock);

           }


        $check = OutletStock::where('outlet_id', $request->outlet_id)->where('medicine_id',$request->medicine_id)->whereDate('expiry_date','=',$request->expiry_date)->first();


        if($check != null){
            $stock2 = array(

                'quantity' => (int)$check->quantity + (int)$request->quantity,
                'price'  => $request->price,
            );

               OutletStock::where('outlet_id', $request->outlet_id)->where('medicine_id',$request->medicine_id)->whereDate('expiry_date','=',$request->expiry_date)->update($stock2);

        }else{

            $check =  OutletStock::create($input);


        }

          try{
            $data = array(
              'outlet_id'    => $check->outlet_id,
              'medicine_distribute_id'    => $request->medicine_distribute_id,
              'medicine_id'    => $check->medicine_id,
              'expiry_date'    => $check->expiry_date,
              'quantity'    => $check->quantity,
               'checked_by' => Auth::user()->id,
               'remarks'    => 'added',

            );
              OutletCheckIn::create($data);

           return redirect()->back()->with('success', ' Successfully Added.');
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
        if($id != 'all'){
            if ($request->ajax()) {
                $data = OutletStock::where("outlet_id",$id)->get();
                return  DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('medicine_name', function($row){
                            $data = Medicine::where('id',$row->medicine_id)->implode('medicine_name');
                          return $data;
                      })

                        ->addColumn('category', function($row){
                            $data = Medicine::where('id',$row->medicine_id)->implode('category_id');
                            $cat = Category::where('id',$data)->implode('category_name');
                            return $cat;
                        })
                        ->addColumn('manufacturer_name', function($row){
                            $data = Medicine::where('id',$row->medicine_id)->implode('manufacturer_id');
                            $manu = Manufacturer::where('id',$data)->implode('manufacturer_name');
                            return $manu;
                        })
                        ->addColumn('unit', function($row){
                            $data = Medicine::where('id',$row->medicine_id)->implode('unit_id');
                            $unit = Unit::where('id',$data)->implode('unit_name');
                            return $unit;
                        })
                        ->addColumn('manufacturer_price', function($row){
                            $data = Medicine::where('id',$row->medicine_id)->implode('manufacturer_price');

                            return $data;
                        })


                        ->rawColumns(['medicine_name'])
                        ->rawColumns(['category'])
                        ->rawColumns(['manufacturer_name'])
                        ->rawColumns(['unit'])

                        ->rawColumns(['manufacturer_price'])

                        ->make(true);
            }
        }else{
            if ($request->ajax()) {
                $data = OutletStock::all();
                return  DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('medicine_name', function($row){
                            $data = Medicine::where('id',$row->medicine_id)->implode('medicine_name');
                          return $data;
                      })

                        ->addColumn('category', function($row){
                            $data = Medicine::where('id',$row->medicine_id)->implode('category_id');
                            $cat = Category::where('id',$data)->implode('category_name');
                            return $cat;
                        })
                        ->addColumn('manufacturer_name', function($row){
                            $data = Medicine::where('id',$row->medicine_id)->implode('manufacturer_id');
                            $manu = Manufacturer::where('id',$data)->implode('manufacturer_name');
                            return $manu;
                        })
                        ->addColumn('unit', function($row){
                            $data = Medicine::where('id',$row->medicine_id)->implode('unit_id');
                            $unit = Unit::where('id',$data)->implode('unit_name');
                            return $unit;
                        })
                        ->addColumn('manufacturer_price', function($row){
                            $data = Medicine::where('id',$row->medicine_id)->implode('manufacturer_price');

                            return $data;
                        })


                        ->rawColumns(['medicine_name'])
                        ->rawColumns(['category'])
                        ->rawColumns(['manufacturer_name'])
                        ->rawColumns(['unit'])

                        ->rawColumns(['manufacturer_price'])

                        ->make(true);
            }
        }


        $outlet = Outlet::pluck('outlet_name', 'id');

      $outlet = new Collection($outlet);
      $outlet->prepend('All Outlet Stock', 'all');


        return view('admin.medicinestock.OutletStock',compact('outlet'));
    }

    public function getoutletStocks(Request $request,$id){


        $search = $request->search;
        if($search == ''){

            $medicines = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $id)->where('outlet_stocks.quantity' ,'>' ,'0')
            ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
            ->select('outlet_stocks.medicine_id as id','outlet_stocks.expiry_date as expiry_date','medicines.medicine_name as medicine_name','medicines.id as medicine_id')->limit(20)->get();

         }else{

            $medicines = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $id )->where('outlet_stocks.quantity' ,'>' ,'0')
            ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
            ->select('outlet_stocks.medicine_id as id','outlet_stocks.expiry_date as expiry_date','medicines.medicine_name as medicine_name' ,'medicines.id as medicine_id')->where('medicine_name', 'like', '%' .$search . '%')->get();



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

      public function get_medicine_details_outlet($id,$id2){


        $product_details = DB::table('outlet_stocks')->where('outlet_stocks.outlet_id', $id2 )->where('outlet_stocks.medicine_id' ,'=' , $id)
        ->leftJoin('medicines', 'outlet_stocks.medicine_id', '=', 'medicines.id')
        ->select('outlet_stocks.*','medicines.medicine_name as medicine_name')->first();

        // $product_details = Medicine::where('id', $id)->select('id','medicine_name','price','manufacturer_price')->first();
        return json_encode($product_details);
  }
}
