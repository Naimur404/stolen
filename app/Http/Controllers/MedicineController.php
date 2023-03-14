<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
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
    public function index()
    {



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
             ->select('id','medicine_name','category_id')->limit(20)
             ->get();
          }else{
            $manufacturer_ids = SupplierHasManufacturer::whereIn('supplier_id', [$request->supplier])
            ->pluck('manufacturer_id');
             $medicines = Medicine::orderby('id','asc')
             ->whereIn('manufacturer_id', $manufacturer_ids)
             ->select('id','medicine_name','category_id')
             ->where('medicine_name', 'like', '%' .$search . '%')->limit(20)
             ->get();
          }

          $response = array();
          foreach($medicines as $medicine){
            $category = Category::where('id',$medicine->category_id)->first();

            $response[] = array(
                 "id"=>$medicine->id,
                 "text"=>$medicine->medicine_name . ' - '. $category->category_name,
            );
          }

          return response()->json($response);
      }
      public function get_all_medicines(Request $request){

        $draw = $request->get('draw');
        $start = $request->get("start");
        $row_per_page = $request->get("length"); // rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = (isset($columnIndex_arr[0]['column']))? $columnIndex_arr[0]['column'] : false; // column index
        $columnName = (isset($columnName_arr[$columnIndex]['data']))? $columnName_arr[$columnIndex]['data'] : false; // column name
        // $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $columnSortOrder = (isset($order_arr[0]['dir']))? $order_arr[0]['dir'] : false; // asc or desc
        $searchValue = (isset($search_arr['value'])) ? $search_arr['value'] : false; // Search value

        // total records count
        $totalRecords = Medicine::select('count(*) as allcount')->count();
        $total_record_switch_filter = Medicine::select('count(*) as allcount')->where('medicine_name', 'like', '%' .$searchValue . '%')->count();

        // fetch records with search
        $medicines = Medicine::orderBy($columnName,$columnSortOrder)
          ->where('medicines.medicine_name', 'like', '%' .$searchValue . '%')
          ->select('medicines.*')
          ->skip($start)
          ->take($row_per_page)
          ->get();

        $data_arr = array();
        $sl = 1;
        foreach($medicines as $medicine){
            $s_no =  $sl++;
        //    $id = $medicine->id;
           $medicine_name = $medicine->medicine_name;
           $generic_name = $medicine->generic_name;
           $category = Category::get_category_name($medicine->category_id);
           $manufacturer_name = Manufacturer::get_manufacturer_name($medicine->manufacturer_id);
           $price = '৳&nbsp;'.$medicine->price;
           $manufacturer_price = '৳&nbsp;' .$medicine->manufacturer_price;

            if(auth()->user()->can('medicine.edit') || auth()->user()->can('medicine.delete')){
                $action ='<a href="'.route('medicine.edit', $medicine->id).'" class="btn btn-success btn-xs mr-1" title="View"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                $action = '<a href="'.route('medicine.edit', $medicine->id).'"
                class="btn btn-success btn-xs" title="Update" style="margin-right:3px"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                $action .= '<a href="'.route('medicine.destroy', $medicine->id).'"
                class="btn btn-danger btn-xs" id="delete" title="Delete" style="margin-right:3px"><i class="fa fa-trash"></i></a>';
            }else{
                $action = 'N/A';
            }

           $data_arr[] = array(
             "id" => $s_no,
             "medicine_name" => $medicine_name,
             "generic_name" => $generic_name,
             "category" => $category,
             "manufacturer_name" => $manufacturer_name,
             "price" => $price,
             "manufacturer_price" => $manufacturer_price,

             "action" => $action
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

}
