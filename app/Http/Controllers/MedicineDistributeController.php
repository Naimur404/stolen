<?php

namespace App\Http\Controllers;

use App\Models\MedicineDistribute;
use App\Models\MedicineDistributeDetail;
use App\Models\Outlet;
use App\Models\OutletHasUser;
use App\Models\StockRequestDetails;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

class MedicineDistributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
         $this->middleware('permission:distribute-medicine.management|distribute-medicine.create|distribute-medicine.edit|distribute-medicine.delete', ['only' => ['index','store']]);
         $this->middleware('permission:distribute-medicine.create', ['only' => ['create','store']]);
         $this->middleware('permission:distribute-medicine.edit', ['only' => ['edit','update']]);
         $this->middleware('permission:distribute-medicine.delete', ['only' => ['destroy']]);
         $this->middleware('permission:distribute-medicine.checkin', ['only' => ['checkIn']]);
     }

    public function index()

    {

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])){
            $medicinedistributes = MedicineDistribute::orderby('id','desc')->get();

        }else{
            $outlet_id = Auth::user()->outlet_id != null  ?  Auth::user()->outlet_id : Outlet::orderby('id','desc')->first('id');
            $medicinedistributes = MedicineDistribute::where('outlet_id',$outlet_id)->where('has_sent','1')->orderby('id','desc')->get();
        }


        return view('admin.DistributeMedicine.index', compact('medicinedistributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $warehouse = Warehouse::pluck('warehouse_name', 'id');
        $outlets = Outlet::pluck('outlet_name', 'id');
        return view('admin.DistributeMedicine.create',compact('warehouse', 'outlets'));
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
            'outlet_id' => 'required',
            'warehouse_id' => 'required'
        ]);



        $input=$request->all();

        // return $input;



        $purchase_input = [
            'warehouse_id' => $input['warehouse_id'],
            'outlet_id' => $input['outlet_id'],
            'date' => $input['purchase_date'],

            'added_by' => Auth::user()->id,

            'remarks' => $input['remarks'],


        ];
        try {

            $medicinedistribute = MedicineDistribute::create($purchase_input);

            $medicines = $input['product_name'];

            for ($i = 0; $i < sizeof($medicines); $i++) {
                $purchase_details = array(

                    'medicine_distribute_id' => $medicinedistribute->id,
                    'medicine_id' => $input['product_id'][$i],
                    'medicine_name' => $input['product_name'][$i],

                    'quantity' => $input['quantity'][$i],
                    'rack_no' => $input['rack_no'][$i],
                    'expiry_date' => $input['expiry_date'][$i],


                    'rate' => $input['total_price'][$i] / $input['quantity'][$i],



                );




                MedicineDistributeDetail::create($purchase_details);
            }


            return redirect()->route('distribute-medicine.index')->with('success', 'Data has been added.');
        } catch (Exception $e) {

             return redirect()->route('distribute-medicine.index')->with('error', $e->getMessage());
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MedicineDistribute  $medicineDistribute
     * @return \Illuminate\Http\Response
     */
    public function show(MedicineDistribute $medicineDistribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MedicineDistribute  $medicineDistribute
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = MedicineDistribute::find($id);

        $medicinedetails = MedicineDistributeDetail::where('medicine_distribute_id',$data->id)->get();
        $warehouse = Warehouse::pluck('warehouse_name', 'id');
        return view('admin.DistributeMedicine.edit',compact('data','medicinedetails','warehouse'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MedicineDistribute  $medicineDistribute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'outlet_id' => 'required',
            'warehouse_id' => 'required'
        ]);
        $input=$request->all();

        // return $input;

// dump($input);

        $purchase_input = [
            'warehouse_id' => $input['warehouse_id'],
            'outlet_id' => $input['outlet_id'],
            'date' => $input['purchase_date'],
            'remarks' => $input['remarks'],
        ];
        try{
          $data =  MedicineDistribute::where('id',$id)->update($purchase_input);
            $medicines = $input['product_name'];

            for ($i = 0; $i < sizeof($medicines); $i++) {

                $purchase_details = array(

                    'medicine_distribute_id' => $id,
                    'medicine_id' => $input['product_id'][$i],
                    'medicine_name' => $input['product_name'][$i],

                    'quantity' => $input['quantity'][$i],
                    'rack_no' => $input['rack_no'][$i],
                    'expiry_date' => $input['expiry_date'][$i],


                    'rate' => $input['total_price'][$i] / $input['quantity'][$i],



                );



                 $check = MedicineDistributeDetail::where('medicine_distribute_id',$id)->where('medicine_id',$input['product_id'][$i])->first();


                 if($check != null){


                    MedicineDistributeDetail::where('medicine_distribute_id',$id)->where('medicine_id',$input['product_id'][$i])->update($purchase_details);

                 }else{

                    MedicineDistributeDetail::create($purchase_details);


                 }


            }


            return redirect()->back()->with('success', 'Data has been Updated.');
        } catch (Exception $e) {

             return redirect()->back()->with('error', $e->getMessage());
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MedicineDistribute  $medicineDistribute
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)

    {
        MedicineDistribute::where('id',$id)->delete();
        MedicineDistributeDetail::where('medicine_distribute_id',$id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted');

    }
    public function medicineDistributeDetailDelete($medicine_id,$distribute_id)
    {
      $data =  MedicineDistributeDetail::where('medicine_id',$medicine_id)->where('medicine_distribute_id',$distribute_id)->delete();


        return redirect()->back()->with('success', 'Data has been Deleted.');
    }
    public function checkIn($id)
    {
        if (Auth::user()->hasRole(['Super Admin', 'Admin'])){

            $productPurchase = MedicineDistribute::findOrFail($id);
            $productPurchaseDetails = MedicineDistributeDetail::where('medicine_distribute_id', $productPurchase->id)->get();

        }else{

            $productPurchase = MedicineDistribute::findOrFail($id);
            $productPurchaseDetails = MedicineDistributeDetail::where('medicine_distribute_id', $productPurchase->id)->where('has_sent','1')->get();

        }



        return view('admin.DistributeMedicine.checkin', compact('productPurchase', 'productPurchaseDetails'));
    }



}
