<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Medicine;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WarehouseCheckIn;
use App\Models\WarehouseStock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Collection;

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
    {
        //
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
    public function update(Request $request, WarehouseStock $warehouseStock)
    {
        //
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
        if($id != 'all'){
            if ($request->ajax()) {
                $data = WarehouseStock::where("warehouse_id",$id)->get();
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

                        ->addColumn('quantity', function($row){
                           return view('admin.action.quantity',compact('row'));
                        })

                        ->rawColumns(['medicine_name'])
                        ->rawColumns(['category'])
                        ->rawColumns(['manufacturer_name'])
                        ->rawColumns(['unit'])

                        ->rawColumns(['manufacturer_price'])


                        ->rawColumns(['quantity'])
                        ->make(true);
            }
        }else{
            if ($request->ajax()) {
                $data = WarehouseStock::all();
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
                  
                        ->addColumn('quantity', function($row){
                            return view('admin.action.quantity',compact('row'));
                         })

                        ->rawColumns(['medicine_name'])
                        ->rawColumns(['category'])
                        ->rawColumns(['manufacturer_name'])
                        ->rawColumns(['unit'])

                        ->rawColumns(['manufacturer_price'])

                        ->rawColumns(['quantity'])
                        ->make(true);
            }
        }


        $warehouse = Warehouse::pluck('warehouse_name', 'id');

      $warehouse = new Collection($warehouse);
      $warehouse->prepend('All Warehouse Stock', 'all');


        return view('admin.medicinestock.warehousestock',compact('warehouse'));
    }
}
