<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:outlet.management|outlet.create|outlet.edit|outlet.delete', ['only' => ['index','store']]);
        $this->middleware('permission:outlet.create', ['only' => ['create','store']]);
        $this->middleware('permission:outlet.edit', ['only' => ['edit','update']]);
        $this->middleware('permission:outlet.delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Outlet::orderBy("id","desc")->get();
            return  DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('active', function($row){
                        $active = route('outlet.status',[$row->id,0]);
                        $inactive = route('outlet.status',[$row->id,1]);
                        return view('admin.action.active',compact('active','inactive','row'));
                    })

                    ->addColumn('action', function($row){
                        $id = $row->id;
                        $edit = route('outlet.edit',$id);
                        $delete = route('outlet.destroy',$id);
                        return view('admin.action.action', compact('id','edit','delete'));
                    })
                    ->rawColumns(['active'])

                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.outlet.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.outlet.create');
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
            'outlet_name' => 'required|string',
            'mobile' => 'required|min:11',


           ]);
           $input = $request->all();
           try{
            Outlet::create($input);

            return redirect()->route('outlet.index')->with('success', ' Successfully Added.');
         }catch(Exception $e){
            return redirect()->route('outlet.index')->with('success', $e->getMessage());
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function show(Outlet $outlet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function edit(Outlet $outlet)
    {
        return view('admin.outlet.edit',compact('outlet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Outlet $outlet)
    {
          $request->validate([
            'outlet_name' => 'required|string',
            'mobile' => 'required|min:11',


           ]);
           $input = $request->all();
           try{
               $outlet->update($input);

            return redirect()->route('outlet.index')->with('success', ' Successfully Added.');
         }catch(Exception $e){
            return redirect()->route('outlet.index')->with('success', $e->getMessage());
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Outlet $outlet)
    {
        try{
            $outlet->delete();

            return redirect()->route('outlet.index')->with('success', ' Successfully Deleted.');
         }catch(Exception $e){
            return redirect()->route('outlet.index')->with('success', $e->getMessage());
         }
    }
    public function active($id,$status){

        $data = Outlet::find($id);
        $data->is_active = $status;
        $data->save();
        return redirect()->route('outlet.index')->with('success','Active Status Updated');

    }
}
