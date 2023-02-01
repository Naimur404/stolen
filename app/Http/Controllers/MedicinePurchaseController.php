<?php

namespace App\Http\Controllers;

use App\Models\MedicinePurchase;
use App\Models\MedicinePurchaseDetails;
use App\Models\PaymentMethod;
use App\Models\Warehouse;
use App\Models\WarehouseCheckIn;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicinePurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $productPurchases = MedicinePurchase::orderBy('id', 'desc')->get();
        return view('admin.medchine_purchase.index', compact('productPurchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $leafs = LeafSetting::all();
        $payment_methods = PaymentMethod::pluck('method_name', 'id');
        $warehouse = Warehouse::pluck('warehouse_name', 'id');
        return view('admin.medchine_purchase.create', compact('payment_methods','warehouse'));
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
            'invoice_image'=> 'mimes:jpeg,jpg,png,ico,JPG|max:2048',
        ]);



        $input=$request->all();

        // return $input;

        if ($request->hasFile('invoice_image')) {
            $file=$request->file('invoice_image');
            $input['invoice_image']=imageUp($file);
        }else{
            $input['invoice_image'] = '';
        }

        if($request->total_discount == null){
             $input['total_discount'] = 0.00;
        }

        if($request->vat == null){
             $input['vat'] = 0.00;
        }

        $purchase_input = [
            'warehouse_id' => $input['warehouse_id'],
            'invoice_no' => $input['invoice_no'],
            'invoice_image' => $input['invoice_image'],

            'purchase_date' => $input['purchase_date'],

            'payment_method_id' => $input['payment_method_id'],
            'supplier_id' => $input['supplier_id'],
            'purchase_details' => $input['purchase_details'],
            'sub_total' => $input['sub_total'],
            'grand_total' => $input['grand_total'],
            'total_discount' => $input['total_discount'],
            'paid_amount' => $input['paid_amount'],
            'due_amount' => $input['due_amount'],
            'vat' => $input['vat'],
            'added_by' => Auth::user()->id,

        ];
        try {
            $purchase = MedicinePurchase::create($purchase_input);

            $medicines = $input['product_name'];

            for ($i = 0; $i < sizeof($medicines); $i++) {
                $purchase_details = array(
                    'warehouse_id' => $purchase->warehouse_id,
                    'medicine_purchase_id' => $purchase->id,
                    'medicine_id' => $input['product_id'][$i],
                    'medicine_name' => $input['product_name'][$i],
                    'prouct_type' => $input['product_type'][$i],
                    'quantity' => $input['quantity'][$i],
                    'rack_no' => $input['rack_no'][$i],
                    'expiry_date' => $input['expiry_date'][$i],
                    'manufacturer_price' => $input['manufacturer_price'][$i],
                    'box_mrp' => $input['box_mrp'][$i],
                    'total_price' => $input['total_price'][$i],
                    'rate' => $input['total_price'][$i] / $input['quantity'][$i],
                    'total_amount' => $purchase->grand_total,
                    'total_discount' => $input['total_discount'],
                    'vat' => $purchase->vat,

                );


                MedicinePurchaseDetails::create($purchase_details);
            }


            return redirect()->back()->with('success', 'Data has been added.');
        } catch (Exception $e) {
             return redirect()->back()->with('error', $e->getMessage());
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\medicinePurchase  $medicinePurchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $medicinePurchase = medicinePurchase::findOrFail($id);
        $medicinePurchaseDetails = medicinePurchaseDetails::where('medicine_purchase_id', $medicinePurchase->id)
            ->get();
        return view('pharmacy.print.medicine_purchase_invoice', compact('medicinePurchase', 'medicinePurchaseDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\medicinePurchase  $medicinePurchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $productPurchase = MedicinePurchase::findOrFail($id);
        $productPurchaseDetails = MedicinePurchaseDetails::where('medicine_purchase_id', $productPurchase->id)
            ->get();
        return view('admin.medchine_purchase.edit', compact('productPurchase', 'productPurchaseDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\medicinePurchase  $medicinePurchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, medicinePurchase $medicinePurchase)
    {
        $input = $request->all();
        $due_amount = $input['due_amount'] - $input['paid_amount'];
        $paid_amount = $medicinePurchase->paid_amount + $input['paid_amount'];
        try {
            $medicinePurchase->update(['due_amount' => $due_amount, 'paid_amount' => $paid_amount]);

            return redirect()->back()->with('success', 'Data has been updated.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\medicinePurchase  $medicinePurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $medicinePurchase = medicinePurchase::findOrFail($id);
        medicinePurchaseDetails::where('medicine_purchase_id', $medicinePurchase->id)->delete();
        $medicinePurchase->delete();
        return redirect()->back()->with('success', 'Data has been Deteled.');
    }


    public function get_all_purchase_medicine(Request $request){

        $search = $request->search;


          if($search == ''){
             $sale_medicines = MedicinePurchaseDetails::distinct()->orderby('id','asc'
             )
             ->where('medicine_type', '=', $request->medicineType)
             ->select('medicine_id','medicine_name', 'medicine_type')
             ->get();
          }else{
             $sale_medicines = medicinePurchaseDetails::distinct()->orderby('id','asc')
             ->where('medicine_type', '=', $request->medicineType)
             ->select('medicine_id','medicine_name','medicine_type')
             ->where('medicine_name', 'like', '%' .$search . '%')
             ->get();
          }

          $response = array();
          foreach($sale_medicines as $medicine){
             $response[] = array(
                  "id"=>$medicine->medicine_id,
                  "text"=>$medicine->medicine_name. '('. $medicine->medicine_type.')'
             );
          }

          return response()->json($response);
      }


    public function get_type_wise_medicine_details($medicine_id, $medicine_type){
        $medicine_details = medicinePurchaseDetails::
                                                where('medicine_id', $medicine_id)
                                                ->where('medicine_type', '=', $medicine_type)
                                                ->select('id','medicine_id','medicine_name','medicine_type','expiry_date','stock_quantity','rate')
                                                ->first();
        $medicine_details->stock_quantity = medicinePurchaseDetails::
                                    where('medicine_id', $medicine_id)
                                        ->where('medicine_type', '=', $medicine_type)
                                        ->sum('stock_quantity');
        return json_encode($medicine_details);
    }
    public function checkIn($id)
    {

        $productPurchase = MedicinePurchase::findOrFail($id);
        $productPurchaseDetails = MedicinePurchaseDetails::where('medicine_purchase_id', $productPurchase->id)->get();
       

        return view('admin.medchine_purchase.checkin', compact('productPurchase', 'productPurchaseDetails'));
    }


}
