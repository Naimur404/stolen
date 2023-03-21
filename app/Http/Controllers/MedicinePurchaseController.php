<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MedicinePurchase;
use App\Models\MedicinePurchaseDetails;
use App\Models\PaymentMethod;
use App\Models\Warehouse;
use App\Models\WarehouseCheckIn;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MedicinePurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:medchine_purchase.management|medchine_purchase.create|medchine_purchase.edit|medchine_purchase.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:medchine_purchase.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:medchine_purchase.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:medchine_purchase.delete', ['only' => ['destroy']]);
        $this->middleware('permission:medchine_purchase.checkin', ['only' => ['checkIn']]);
    }

    public function index()
    {
        $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');
        if (Auth::user()->hasRole(['Super Admin','Admin'])) {
            $productPurchases = MedicinePurchase::orderBy('id', 'desc')->get();
        } else {
            $productPurchases = MedicinePurchase::where('warehouse_id', $warehouse_id)->orderBy('id', 'desc')->get();
        }

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
        $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');
        $payment_methods = PaymentMethod::pluck('method_name', 'id');
        if (Auth::user()->hasrole('Super Admin')) {

            $warehouse = Warehouse::pluck('warehouse_name', 'id');
        } else {
            $warehouse = Warehouse::where('id', $warehouse_id)->pluck('warehouse_name', 'id');
        }

        return view('admin.medchine_purchase.create', compact('payment_methods', 'warehouse'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_image' => 'mimes:jpeg,jpg,png,ico,JPG|max:2048',
        ]);


        $input = $request->all();

        // return $input;

        if ($request->hasFile('invoice_image')) {
            $file = $request->file('invoice_image');
            $input['invoice_image'] = imageUp($file);
        } else {
            $input['invoice_image'] = '';
        }

        if ($request->total_discount == null) {
            $input['total_discount'] = 0.00;
        }

        if ($request->vat == null) {
            $input['vat'] = 0.00;
        }

        $purchase_input = [
            'warehouse_id' => (int)$input['warehouse_id'],
            'invoice_no' => $input['invoice_no'],
            'invoice_image' => $input['invoice_image'],
            'purchase_date' => Carbon::parse($input['purchase_date'])->toDateString(),
            'payment_method_id' => (int)$input['payment_method_id'],
            'supplier_id' => (int)$input['supplier_id'],
            'purchase_details' => $input['purchase_details'],
            'sub_total' => (double)$input['sub_total'],
            'grand_total' => (double)$input['grand_total'],
            'total_discount' => (double)$input['total_discount'],
            'paid_amount' => round($input['paid_amount']),
            'due_amount' => (double)$input['due_amount'],
            'vat' => (double)$input['vat'],
            'added_by' => Auth::user()->id,

        ];
        try {
            Log::info($purchase_input);
            $purchase = MedicinePurchase::create($purchase_input);

            $medicines = $input['product_name'];
            Log::info($medicines);

            for ($i = 0; $i < sizeof($medicines); $i++) {
                $purchase_details = array(
                    'warehouse_id' => (int)$input['warehouse_id'],
                    'medicine_purchase_id' => $purchase->id,
                    'medicine_id' => (int)$input['product_id'][$i],
                    'medicine_name' => $input['product_name'][$i],
                    'product_type' => $input['product_type'][$i],
                    'quantity' => (int)$input['quantity'][$i],
                    'rack_no' => $input['rack_no'][$i],
                    'expiry_date' => Carbon::parse($input['expiry_date'][$i])->toDateString(),
                    'manufacturer_price' => (double)$input['manufacturer_price'][$i],
                    'box_mrp' => (double)$input['box_mrp'][$i],
                    'total_price' => (double)$input['total_price'][$i],
                    'rate' => round($input['total_price'][$i] / $input['quantity'][$i], 2),
                    'total_amount' => (double)$input['total_price'][$i],
                    'total_discount' => (double)$input['total_discount'],
                    'vat' => (double)$purchase->vat,

                );

                Log::info($purchase_details);

                $details = MedicinePurchaseDetails::create($purchase_details);
                Log::info($details);
            }


            return redirect()->back()->with('success', 'Data has been added.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\medicinePurchase $medicinePurchase
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
     * @param \App\medicinePurchase $medicinePurchase
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
     * @param \Illuminate\Http\Request $request
     * @param \App\medicinePurchase $medicinePurchase
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
     * @param \App\medicinePurchase $medicinePurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $medicinePurchase = medicinePurchase::findOrFail($id);
        medicinePurchaseDetails::where('medicine_purchase_id', $medicinePurchase->id)->delete();
        $medicinePurchase->delete();
        return redirect()->back()->with('success', 'Data has been Deteled.');
    }


    public function get_all_purchase_medicine(Request $request)
    {

        $search = $request->search;


        if ($search == '') {
            $sale_medicines = MedicinePurchaseDetails::distinct()->orderby('id', 'asc'
            )
                ->where('medicine_type', '=', $request->medicineType)
                ->select('medicine_id', 'medicine_name', 'medicine_type','category_id')
                ->get();
        } else {
            $sale_medicines = medicinePurchaseDetails::distinct()->orderby('id', 'asc')
                ->where('medicine_type', '=', $request->medicineType)
                ->select('medicine_id', 'medicine_name', 'medicine_type','category_id')
                ->where('medicine_name', 'like', '%' . $search . '%')
                ->get();
        }

        $response = array();
        foreach($sale_medicines as $medicine){
            $category = Category::where('id',$medicine->category_id)->first();

            $response[] = array(
                 "id"=>$medicine->id,
                 "text"=>$medicine->medicine_name . ' - '. $category->category_name,
            );
          }

        return response()->json($response);
    }


    public function get_type_wise_medicine_details($medicine_id, $medicine_type)
    {
        $medicine_details = medicinePurchaseDetails::
        where('medicine_id', $medicine_id)
            ->where('medicine_type', '=', $medicine_type)
            ->select('id', 'medicine_id', 'medicine_name', 'medicine_type', 'expiry_date', 'stock_quantity', 'rate')
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
public function editPurchase($id){
    $data = MedicinePurchaseDetails::where('id',$id)->first();
    $productPurchase = MedicinePurchase::where('id',$data->medicine_purchase_id)->first();
    return view('admin.medchine_purchase.edit_purchase',compact('data','productPurchase'));
}
public function purchaseUpdate(Request $request){

// dump($request->all());
$data = MedicinePurchaseDetails::where('id',$request->pdid)->first();
try{

    $update = array(
    'quantity' => $request->qty,
    'manufacturer_price' => $request->price,
    'total_price' => round($request->qty*$request->price,2),
    'total_amount' => round($request->qty*$request->price,2),

    );
    $updatepurchase =  MedicinePurchaseDetails::where('id',$request->pdid)->update($update);
    $data2 = MedicinePurchaseDetails::where('medicine_purchase_id',$request->pid)->get();
    $total = 0;
  foreach($data2  as $final){
   $total = $total+$final->total_price;
  }

  $data3 = MedicinePurchase::where('id',$request->pid)->first();
  $check1 = round($total-($data3->vat+$data3->total_discount),2);
  if($data3->paid_amount > $check1){
    $check = 0;
  }else{
    $check = round($total-($data3->vat+$data3->total_discount) - ($data3->paid_amount),2);
  }
  $update2 = array(
'sub_total' => $total,
'grand_total' => round($total-($data3->vat+$data3->total_discount),2),
'due_amount' => $check,
'paid_amount' => $data3->paid_amount,
  );
  MedicinePurchase::where('id',$request->pid)->update($update2);
  return redirect()->route('medicine-purchase.edit', $request->pid)->with('succes', 'Updated');
}catch(Exception $e){
    return redirect()->back()->with('error', $e->getMessage());
}


}

}
