<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
         $this->middleware('permission:manufacturer.management|manufacturer.create|manufacturer.edit|manufacturer.delete', ['only' => ['index','store']]);
         $this->middleware('permission:manufacturer.create', ['only' => ['create','store']]);
         $this->middleware('permission:manufacturer.edit', ['only' => ['edit','update']]);
         $this->middleware('permission:manufacturer.delete', ['only' => ['destroy']]);
     }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Manufacturer::orderBy("id","desc")->get();
            return  DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('active', function($row){
                        $active = route('manufacturer.status',[$row->id,0]);
                        $inactive = route('manufacturer.status',[$row->id,1]);
                        return view('admin.action.active',compact('active','inactive','row'));
                    })

                    ->addColumn('action', function($row){
                        $id = $row->id;
                        $edit = route('manufacturer.edit',$id);
                        $delete = route('manufacturer.destroy',$id);
                        return view('admin.action.action', compact('id','edit','delete'));
                    })
                    ->rawColumns(['active'])

                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.manufacturer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.manufacturer.create');
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
            'manufacturer_name' => 'required|string',
            'mobile' => 'required|min:11',


           ]);
           $input = $request->all();
           try{
            Manufacturer::create($input);

            return redirect()->route('manufacturer.index')->with('success', ' Successfully Added.');
         }catch(Exception $e){
            return redirect()->route('manufacturer.index')->with('success', $e->getMessage());
         }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function show(Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function edit(Manufacturer $manufacturer)
    {
        return view('admin.manufacturer.edit',compact('manufacturer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manufacturer $manufacturer)
    {
        $request->validate([
            'manufacturer_name' => 'required|string',
            'mobile' => 'required|min:11',


           ]);
           $input = $request->all();

           try{
            $manufacturer->update($input);

            return redirect()->route('manufacturer.index')->with('success', ' Successfully Added.');
         }catch(Exception $e){
            return redirect()->route('manufacturer.index')->with('success', $e->getMessage());
         }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manufacturer $manufacturer)
    {

        try{
            $manufacturer->delete();

            return redirect()->route('manufacturer.index')->with('success', ' Successfully Added.');
         }catch(Exception $e){
            return redirect()->route('manufacturer.index')->with('success', $e->getMessage());
         }

    }
    public function active($id,$status){

        $data = Manufacturer::find($id);
        $data->is_active = $status;
        $data->save();
        return redirect()->route('manufacturer.index')->with('success','Active Status Updated');

    }
    public function getManufacturer(Request $request){
        $search = $request->search;

        if($search == ''){
            $Manufacturers = Manufacturer::orderby('manufacturer_name','asc')->select('id','manufacturer_name')->limit(5)->get();
        }else{
           $Manufacturers = Manufacturer::orderby('manufacturer_name','asc')->select('id','manufacturer_name')->where('manufacturer_name', 'like', '%' .$search . '%')->limit(5)->get();
        }

        $response = array();
        foreach($Manufacturers as $Manufacturer){
           $response[] = array(
                "id"=>$Manufacturer->id,
                "text"=>$Manufacturer->manufacturer_name
           );
        }
        return response()->json($response);
     }
}
