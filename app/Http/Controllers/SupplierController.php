<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Models\Supplier;
use App\Models\SupplierHasManufacturer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:supplier.management|supplier.create|supplier.edit|supplier.delete', ['only' => ['index','store']]);
        $this->middleware('permission:supplier.create', ['only' => ['create','store']]);
        $this->middleware('permission:supplier.edit', ['only' => ['edit','update']]);
        $this->middleware('permission:supplier.delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::all();
            return  DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('manufacturer', function($row){

                        return view('admin.action.manufacturer', compact('row'));
                    })
                    ->addColumn('active', function($row){
                        $active = route('supplier.active',[$row->id,0]);
                        $inactive = route('supplier.active',[$row->id,1]);
                        return view('admin.action.active',compact('active','inactive','row'));
                    })
                    ->addColumn('action', function($row){
                        $id = $row->id;
                        $edit = route('supplier.edit',$id);
                        $delete = route('supplier.destroy',$id);
                        return view('admin.action.action', compact('id','edit','delete'));
                    })
                    ->rawColumns(['manufacturer'])
                    ->rawColumns(['active'])
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.supplier.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'manufacturer_id' => 'required',
            'supplier_name' => 'required|string',
            'mobile' => 'required|min:11',

           ]);
        $input = $request->all();

        $manufacturers = $request->input('manufacturer_id');
        try{
             $supplier = Supplier::create($input);

            foreach ($manufacturers as $manufacturer) {
                SupplierHasManufacturer::create([
                    'manufacturer_id' => $manufacturer,
                    'supplier_id' => $supplier->id,

                ]);
            }


             return redirect()->back()->with('success', 'Data has been added.');
        }catch(\Exception $e){
               return redirect()->back()->with('success', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {

        $manufacturers = Manufacturer::all();
        $exist_manufacturer = SupplierHasManufacturer::whereIn('supplier_id', [$supplier->id])->get();
        return view('admin.supplier.edit',compact('supplier','manufacturers', 'exist_manufacturer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $input = $request->all();
        try{
            $supplier->update($input);
            SupplierHasManufacturer::whereIn('supplier_id', [$supplier->id])->delete();
            $manufacturers = $request->input('manufacturer_id');

            foreach ($manufacturers as $manufacturer){
                SupplierHasManufacturer::create([

                    'supplier_id' => $supplier->id,
                    'manufacturer_id' => $manufacturer,
                ]);
            }
            return redirect()->back()->with('success', 'Data has been Update.');
       }catch(\Exception $e){
              return redirect()->back()->with('success', $e->getMessage());
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {

        SupplierHasManufacturer::whereIn('supplier_id', [$supplier->id])->delete();
        $supplier->delete();

        return redirect()->back()->with('success', 'Data has been Deleted.');
    }
    public function active($id,$status){

        $data = Supplier::find($id);
        $data->is_active = $status;
        $data->save();
        return redirect()->route('supplier.index')->with('success','Active Status Updated');

    }

}
