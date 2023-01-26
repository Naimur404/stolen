<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Warehouse::orderBy("id","desc")->get();
            return  DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('active', function($row){
                        $active = route('warehouse.status',[$row->id,0]);
                        $inactive = route('warehouse.status',[$row->id,1]);
                        return view('admin.action.active',compact('active','inactive','row'));
                    })

                    ->addColumn('action', function($row){
                        $id = $row->id;
                        $edit = route('warehouse.edit',$id);
                        $delete = route('warehouse.destroy',$id);
                        return view('admin.action.action', compact('id','edit','delete'));
                    })
                    ->rawColumns(['active'])

                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.warehouse.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.warehouse.create');
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
            'warehouse_name' => 'required|string',
            'mobile' => 'required|min:11',


           ]);
           $input = $request->all();
           try{
            Warehouse::create($input);

            return redirect()->route('warehouse.index')->with('success', ' Successfully Added.');
         }catch(Exception $e){
            return redirect()->route('warehouse.index')->with('success', $e->getMessage());
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        return view('admin.warehouse.edit',compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'warehouse_name' => 'required|string',
            'mobile' => 'required|min:11',


           ]);
           $input = $request->all();
           try{
               $warehouse->update($input);

            return redirect()->route('warehouse.index')->with('success', ' Successfully Added.');
         }catch(Exception $e){
            return redirect()->route('warehouse.index')->with('success', $e->getMessage());
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        try{
            $warehouse->delete();

            return redirect()->route('warehouse.index')->with('success', ' Successfully Added.');
         }catch(Exception $e){
            return redirect()->route('warehouse.index')->with('success', $e->getMessage());
         }
    }
    public function active($id,$status){

        $data = Warehouse::find($id);
        $data->is_active = $status;
        $data->save();
        return redirect()->route('warehouse.index')->with('success','Active Status Updated');

    }
}
