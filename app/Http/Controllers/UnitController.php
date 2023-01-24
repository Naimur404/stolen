<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:unit.management|unit.create|unit.edit|unit.delete', ['only' => ['index','store']]);
        $this->middleware('permission:unit.create', ['only' => ['create','store']]);
        $this->middleware('permission:unit.edit', ['only' => ['edit','update']]);
        $this->middleware('permission:unit.delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $data = Unit::all();
        if ($request->ajax()) {
            $data = Unit::orderBy("id","desc")->get();
            return  DataTables::of($data)
                    ->addIndexColumn()
                        ->addColumn('action', function($row){
                        $id = $row->id;

                        $edit = route('unit.edit',$id);
                        $delete = route('unit.destroy',$id);
                        $permission = 'unit.edit';
                        $permissiondelete = 'unit.delete';
                        return view('admin.action.action', compact('id','edit','delete','permission','permissiondelete'));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.unit.unit',compact('data'));
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

        $request->validate([
            'unit_name' => 'required|string',


        ]);


        $unit_name = Unit::create($request->all());
         return response()->json($unit_name);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        $unit_name = Unit::find($unit->id);
        return response()->json($unit_name);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'unit_name' => 'required|string',


        ]);
        $input = $request->all();
            $unit_name =$unit->update($input);



            return response()->json($unit_name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('unit.index')->with('success','Delete successfully');
    }
}
