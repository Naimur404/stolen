<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicinePurchaseDetails;
use App\Models\SupplierHasManufacturer;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:medicine.management|medicine.create|medicine.edit|medicine.delete', ['only' => ['index','store']]);
        $this->middleware('permission:medicine.create', ['only' => ['create','store']]);
        $this->middleware('permission:medicine.edit', ['only' => ['edit','update']]);
        $this->middleware('permission:medicine.delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Medicine::orderBy("id","desc")->get();
            return  DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('manufacturer', function($row){
                        $man_id = $row->manufacturer_id;
                      return view('admin.action.manufacture',compact('man_id'));
                  })

                    ->addColumn('category', function($row){
                          $cat_id = $row->category_id;
                        return view('admin.action.category',compact('cat_id'));
                    })
                    ->addColumn('image', function($row){
                        $image = $row->image;
                      return view('admin.action.image',compact('image'));
                  })

                    ->addColumn('action', function($row){
                        $id = $row->id;
                        $edit = route('medicine.edit',$id);
                        $delete = route('medicine.destroy',$id);
                        return view('admin.action.action', compact('id','edit','delete'));
                    })
                    ->rawColumns(['manufacturer'])
                    ->rawColumns(['category'])
                    ->rawColumns(['image'])

                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.medchine.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.medchine.create');
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

            'category_id' => 'required',
            'unit_id' => 'required',
            // 'type_id',
            'medicine_name' =>'required|string',
            'generic_name' =>'required|string',
            'price' => 'required',
            'manufacturer_id' => 'required',
            'manufacturer_price'=>'required',
           ]);
           $input = $request->all();

           if ($request->hasFile('image')) {
            $request->validate([

                'image' =>'mimes:png,jpg,jpeg,gif'
             ]);
            $file=$request->file('image');
            $input['image']=imageUp($file);
        }
        try {
            Medicine::create($input);
            return redirect()->back()->with('success', 'Data has been added.');
        }
        catch (Exception $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function show(Medicine $medicine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function edit(Medicine $medicine)
    {
        return view('admin.medchine.edit',compact('medicine'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([

            'category_id' => 'required',
            'unit_id' => 'required',
            // 'type_id',
            'medicine_name' =>'required|string',
            'generic_name' =>'required|string',
            'price' => 'required',
            'manufacturer_id' => 'required',
            'manufacturer_price'=>'required',
           ]);
           $input = $request->all();

           if ($request->hasFile('image')) {
            $request->validate([

                'image' =>'mimes:png,jpg,jpeg,gif'
             ]);
             if(file_exists(public_path('uploads/'.$medicine->image)) && !is_null($medicine->image)){
                unlink(public_path('uploads/'.$medicine->image));
            }
            $file=$request->file('image');
            $input['image']=imageUp($file);
        }
        try {
            $medicine->update($input);
            return redirect()->back()->with('success', 'Data has been Updated.');
        }
        catch (Exception $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medicine $medicine)
    {
        if($medicine->image !=''){
            unlink(public_path('uploads/'.$medicine->image));
            }
        try{
            $medicine->delete();

            return redirect()->route('medicine.index')->with('success', ' Successfully Delete.');
         }catch(Exception $e){
            return redirect()->route('medicine.index')->with('success', $e->getMessage());
         }
    }


      // get product details for purchase
      public function get_medicine_details_for_purchase($id){
            $product_details = Medicine::where('id', $id)->select('id','medicine_name','price','manufacturer_price')->first();
            return json_encode($product_details);
      }


    // select product for sale



      public function get_manufacturer_wise_medicine(Request $request){

        $search = $request->search;
          if($search == ''){
            $manufacturer_ids = SupplierHasManufacturer::whereIn('supplier_id', [$request->supplier])
            ->pluck('manufacturer_id');
             $medicines = Medicine::orderby('id','asc')
             ->whereIn('manufacturer_id', $manufacturer_ids)
             ->select('id','medicine_name')
             ->get();
          }else{
            $manufacturer_ids = SupplierHasManufacturer::whereIn('supplier_id', [$request->supplier])
            ->pluck('manufacturer_id');
             $medicines = Medicine::orderby('id','asc')
             ->whereIn('manufacturer_id', $manufacturer_ids)
             ->select('id','medicine_name')
             ->where('medicine_name', 'like', '%' .$search . '%')
             ->get();
          }

          $response = array();
          foreach($medicines as $medicine){
             $response[] = array(
                  "id"=>$medicine->id,
                  "text"=>$medicine->medicine_name
             );
          }

          return response()->json($response);
      }
    

}
