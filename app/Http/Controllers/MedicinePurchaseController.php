<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MedicinePurchase;
use App\Models\MedicinePurchaseDetails;
use App\Models\PaymentMethod;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\WarehouseCheckIn;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MedicinePurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:medchine_purchase.management|medchine_purchase.create|medchine_purchase.edit|medchine_purchase.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:medchine_purchase.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:medchine_purchase.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:medchine_purchase.delete', ['only' => ['destroy']]);
        $this->middleware('permission:medchine_purchase.checkin', ['only' => ['checkIn']]);
    }

    public function index()
    {
        return view('admin.medchine_purchase.index');
    }

    public function medicinePurchase(Request $request)
    {

        $draw = $request->get('draw');
        $start = $request->get("start");
        $row_per_page = $request->get("length"); // rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = (isset($columnIndex_arr[0]['column'])) ? $columnIndex_arr[0]['column'] : false; // column index
        $columnName = (isset($columnName_arr[$columnIndex]['data'])) ? $columnName_arr[$columnIndex]['data'] : false; // column name
        $columnSortOrder = (isset($order_arr[0]['dir'])) ? $order_arr[0]['dir'] : false; // asc or desc
        $searchValue = (isset($search_arr['value'])) ? $search_arr['value'] : false; // Search value

        $warehouse_id = Auth::user()->warehouse_id != null ? Auth::user()->warehouse_id : Warehouse::orderby('id', 'desc')->first('id');

        if (Auth::user()->hasRole(['Super Admin', 'Admin'])) {
            $totalRecords = MedicinePurchase::select('count(*) as allcount')->count();
            $productPurchases = DB::table('medicine_purchases')
                ->where('invoice_no', 'like', '%' . $searchValue . '%')->where('deleted_at', null)
                ->orderBy($columnName, $columnSortOrder)
                ->skip($start)
                ->take($row_per_page)
                ->get();
        } else {
            $totalRecords = MedicinePurchase::where('warehouse_id', $warehouse_id)->select('count(*) as allcount')->count();
            $productPurchases = DB::table('medicine_purchases')->where('warehouse_id', $warehouse_id)->where('deleted_at', null)
                ->where('invoice_no', 'like', '%' . $searchValue . '%')
                ->orderBy($columnName, $columnSortOrder)
                ->skip($start)
                ->take($row_per_page)
                ->get();
        }
        $total_record_switch_filter = $totalRecords;
        $data_arr = array();
        $total = 0;
        foreach ($productPurchases as $productPurchase) {

            $edit = route('medicine-purchase.edit', $productPurchase->id);
            $delete = route('medicinePurchaseDelete', $productPurchase->id);
            $checkIn = route('medicine-purchase.checkIn', $productPurchase->id);

            $s_no = $productPurchase->id;
            $supplier = Supplier::getSupplierName($productPurchase->supplier_id);
            $purchase_date = Carbon::parse($productPurchase->purchase_date)->format('d-m-Y');
            $payment = PaymentMethod::getPayment($productPurchase->payment_method_id);
            $total = $productPurchase->grand_total;
            $pay = $productPurchase->paid_amount;
            $due = $productPurchase->due_amount;
            // $sold_by = User::getUser($productPurchase->added_by);
            $data1 = MedicinePurchaseDetails::where('medicine_purchase_id', $productPurchase->id)->get();
            $data = WarehouseCheckIn::where('purchase_id', $productPurchase->id)->get();
            if (count($data1) == count($data)) {
                $action = '<a href="javascript:void()"class="btn btn-warning btn-xs" title="Sent" style="margin-right:5px"><i class="fa fa-check" aria-hidden="true"></i></a>
                <a href="' . $delete . '" class="btn btn-danger btn-xs " title="CheckIn" style="margin-left:3px"><i class="fa fa-trash"></i></a>
                <a href="' . $checkIn . '" class="btn btn-info btn-xs " title="CheckIn" style="margin-left:3px"><i class="fa fa-eye" aria-hidden="true"></i></a>';
            } else {

                $action = '<a href="' . $edit . '"class="btn btn-success btn-xs" title="Sent" style="margin-right:5px"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                <a href="' . $delete . '" class="btn btn-danger btn-xs " title="Delete" style="margin-left:3px"><i class="fa fa-trash"></i></a>
                <a href="' . $checkIn . '" class="btn btn-info btn-xs " title="CheckIn" style="margin-left:3px"><i class="fa fa-eye" aria-hidden="true"></i></a>';
            }

            $data_arr[] = array(
                "id" => $s_no,
                "supplier_id" => $supplier,
                "purchase_date" => $purchase_date,
                "payment_method_id" => $payment,
                "grand_total" => $total,
                "paid_amount" => $pay,
                "due_amount" => $due,
                "action" => $action,

            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $total_record_switch_filter,
            "aaData" => $data_arr,

        );

        return response()->json($response);
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
            'warehouse_id' => (int) $input['warehouse_id'],
            'invoice_no' => $input['invoice_no'],
            'invoice_image' => $input['invoice_image'],
            'purchase_date' => Carbon::parse($input['purchase_date'])->toDateString(),
            'payment_method_id' => (int) $input['payment_method_id'],
            'supplier_id' => (int) $input['supplier_id'],
            'purchase_details' => $input['purchase_details'],
            'sub_total' => (double) $input['sub_total'],
            'grand_total' => (double) $input['grand_total'],
            'total_discount' => (double) $input['total_discount'],
            'paid_amount' => round($input['paid_amount']),
            'due_amount' => (double) $input['due_amount'],
            'vat' => (double) $input['vat'],
            'added_by' => Auth::user()->id,

        ];
        try {

            $supplier = Supplier::where('id', $input['supplier_id'])->first();
            $supplier_due = array(
                'due_balance' => $supplier->due_balance + $input['due_amount'],
            );
            Supplier::where('id', $input['supplier_id'])->update($supplier_due);

            $purchase = MedicinePurchase::create($purchase_input);
            $medicines = $input['product_name'];

            if ($input['total_discount'] != '' || $input['total_discount'] > 0) {

                $percent = ($input['total_discount'] / $input['sub_total']) * 100;
                $percent = number_format($percent, 2);

                for ($i = 0; $i < sizeof($medicines); $i++) {

                    $manuprice = $input['manufacturer_price'][$i] - ($input['manufacturer_price'][$i] * ($percent / 100));
                    $purchase_details = array(
                        'warehouse_id' => (int) $input['warehouse_id'],
                        'medicine_purchase_id' => $purchase->id,
                        'medicine_id' => (int) $input['product_id'][$i],
                        'medicine_name' => $input['product_name'][$i],
                        'product_type' => $input['product_type'][$i],
                        'quantity' => (int) $input['quantity'][$i],
                        'rack_no' => $input['rack_no'][$i],
                        'expiry_date' => Carbon::parse($input['expiry_date'][$i])->toDateString(),
                        'manufacturer_price' => (double)($manuprice),
                        'box_mrp' => (double) $input['box_mrp'][$i],
                        'total_price' => (double) $input['total_price'][$i],
                        'rate' => round($input['total_price'][$i] / $input['quantity'][$i], 2),
                        'total_amount' => (double) $input['total_price'][$i],
                        'total_discount' => (double)(($input['manufacturer_price'][$i] * ($percent / 100)) * $input['quantity'][$i]),
                        'vat' => (double) $purchase->vat,

                    );

                    MedicinePurchaseDetails::create($purchase_details);
                }

            } else {
                for ($i = 0; $i < sizeof($medicines); $i++) {
                    $purchase_details = array(
                        'warehouse_id' => (int) $input['warehouse_id'],
                        'medicine_purchase_id' => $purchase->id,
                        'medicine_id' => (int) $input['product_id'][$i],
                        'medicine_name' => $input['product_name'][$i],
                        'product_type' => $input['product_type'][$i],
                        'quantity' => (int) $input['quantity'][$i],
                        'rack_no' => $input['rack_no'][$i],
                        'expiry_date' => Carbon::parse($input['expiry_date'][$i])->toDateString(),
                        'manufacturer_price' => (double) $input['manufacturer_price'][$i],
                        'box_mrp' => (double) $input['box_mrp'][$i],
                        'total_price' => (double) $input['total_price'][$i],
                        'rate' => round($input['total_price'][$i] / $input['quantity'][$i], 2),
                        'total_amount' => (double) $input['total_price'][$i],
                        'total_discount' => (double) $input['total_discount'],
                        'vat' => (double) $purchase->vat,

                    );

                    MedicinePurchaseDetails::create($purchase_details);
                }
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

        $productPurchase = MedicinePurchase::findOrFail($id);
        $productPurchaseDetails = MedicinePurchaseDetails::where('medicine_purchase_id', $productPurchase->id)
            ->get();
        return view('admin.medchine_purchase.show', compact('productPurchase', 'productPurchaseDetails'));
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
    public function delete($id)
    {
        medicinePurchaseDetails::where('medicine_purchase_id', $id)->Delete();
        $medicinePurchase = medicinePurchase::findOrFail($id);
        $medicinePurchase->Delete();

        return redirect()->back()->with('success', 'Data has been Deleted.');
    }

    public function get_all_purchase_medicine(Request $request)
    {

        $search = $request->search;

        if ($search == '') {
            $sale_medicines = MedicinePurchaseDetails::distinct()->orderby('id', 'asc'
            )
                ->where('medicine_type', '=', $request->medicineType)
                ->select('medicine_id', 'medicine_name', 'medicine_type', 'category_id')
                ->get();
        } else {
            $sale_medicines = medicinePurchaseDetails::distinct()->orderby('id', 'asc')
                ->where('medicine_type', '=', $request->medicineType)
                ->select('medicine_id', 'medicine_name', 'medicine_type', 'category_id')
                ->where('medicine_name', 'like', '%' . $search . '%')
                ->get();
        }

        $response = array();
        foreach ($sale_medicines as $medicine) {
            $category = Category::where('id', $medicine->category_id)->first();

            $response[] = array(
                "id" => $medicine->id,
                "text" => $medicine->medicine_name . ' - ' . $category->category_name,
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
    public function editPurchase($id)
    {
        $data = MedicinePurchaseDetails::where('id', $id)->first();
        $productPurchase = MedicinePurchase::where('id', $data->medicine_purchase_id)->first();
        return view('admin.medchine_purchase.edit_purchase', compact('data', 'productPurchase'));
    }
    public function purchaseUpdate(Request $request)
    {

// dump($request->all());
        $data = MedicinePurchaseDetails::where('id', $request->pdid)->first();
        try {

            $update = array(
                'expiry_date' => Carbon::parse($request->purchase_date)->toDateString(),
                'quantity' => $request->qty,
                'manufacturer_price' => $request->price,
                'rate' => $request->box_mrp,
                'total_price' => round($request->qty * $request->price, 2),
                'total_amount' => round($request->qty * $request->price, 2),

            );
            $updatepurchase = MedicinePurchaseDetails::where('id', $request->pdid)->update($update);
            $data2 = MedicinePurchaseDetails::where('medicine_purchase_id', $request->pid)->get();
            $total = 0;
            foreach ($data2 as $final) {
                $total = $total + $final->total_price;
            }

            $data3 = MedicinePurchase::where('id', $request->pid)->first();
            $check1 = round($total - ($data3->vat + $data3->total_discount), 2);
            if ($data3->paid_amount > $check1) {
                $check = 0;
            } else {
                $check = round($total - ($data3->vat + $data3->total_discount) - ($data3->paid_amount), 2);
            }
            $update2 = array(
                'sub_total' => $total,
                'grand_total' => round($total - ($data3->vat + $data3->total_discount), 2),
                'due_amount' => $check,
                'paid_amount' => $data3->paid_amount,
            );

            $final_data = MedicinePurchase::where('id', $request->pid)->update($update2);
            $suppler = Supplier::where('id', $final_data->supplier_id)->first();
            $suppler_due = array(
                'due_amount' => $suppler->due_amount + $check,
            );

            Supplier::where('id', $suppler->id)->update($suppler_due);
            return redirect()->route('medicine-purchase.edit', $request->pid)->with('success', 'Updated');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function purchaseDelete($id)
    {
        $data = MedicinePurchaseDetails::where('id', $id)->first();
        $data2 = MedicinePurchase::where('id', $data->medicine_purchase_id)->first();
        $subtotal = $data2->sub_total - $data->total_price;
        $grand_total = $subtotal - ($data2->vat + $data2->total_discount);
        $due_amount = $grand_total - $data2->paid_amount;

        if ($due_amount <= 0) {
            $due_amount = 0;
        } else {
            $due_amount = $grand_total - $data2->paid_amount;
        }

        $data3 = array(
            'sub_total' => $subtotal,
            'grand_total' => $grand_total,
            'due_amount' => $due_amount,
        );

        MedicinePurchase::where('id', $data->medicine_purchase_id)->update($data3);
        MedicinePurchaseDetails::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been Deleted.');
    }

}
