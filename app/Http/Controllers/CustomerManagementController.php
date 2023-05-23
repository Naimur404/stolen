<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerDuePayment;
use App\Models\Outlet;
use App\Models\OutletInvoice;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:customer.management|customer.create|customer.edit|customer.delete', ['only' => ['customer', 'store']]);
        $this->middleware('permission:customer.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customer.delete', ['only' => ['destroy']]);
        $this->middleware('permission:customer-due', ['only' => ['customerDue']]);
        $this->middleware('permission:customer-due-payment', ['only' => ['customerDuePayment']]);
    }

    public function index()
    {
        if (auth()->user()->hasrole(['Super Admin', 'Admin'])) {

            $outlet = Outlet::pluck('outlet_name', 'id');
            $outlet = new Collection($outlet);
            $outlet->prepend('All Outlet Customer', 'all');
        } else {
            $outlet = Outlet::where('id', Auth::user()->outlet_id)->pluck('outlet_name', 'id');
        }

        return view('admin.customermanagement.index', compact('outlet'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customermanagement.create');
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
            'name' => 'required|string',
            'mobile' => 'required|min:11',
            'outlet_id' => 'required|string',

        ]);
        $input = $request->all();
        $data = array(
            'name' => $input['name'],
            'mobile' => $input['mobile'],
            'address' => $input['address'],
            'points' => 0,
            'outlet_id' => $input['outlet_id'],

        );

        try {
            Customer::create($data);
            return redirect()->back()->with('success', ' New Customer Added');
        } catch (Exception $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Customer $customerManagement
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customerManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Customer $customerManagement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customerManagement = Customer::find($id);
        return view('admin.customermanagement.edit', compact('customerManagement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Customer $customerManagement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|min:11',
            'outlet_id' => 'required|string',

        ]);
        $input = $request->all();
        $data = array(
            'name' => $input['name'],
            'mobile' => $input['mobile'],
            'address' => $input['address'],
            'points' => $input['points'],
            'outlet_id' => $input['outlet_id'],

        );

        try {
            Customer::where('id', $request->id)->update($data);
            return redirect()->back()->with('success', 'Customer Update');
        } catch (Exception $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Customer $customerManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Customer::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been Deleted.');
    }

    public function customerDelete($id)
    {
        try {
            $customer = Customer::find($id);

            $customer->delete();

            return redirect()->back()->with('success', 'Data has been Deleted.');
        } catch (Exception $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }
    }

    public function customer(Request $request, $id)
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

        // total records count
        if ($id == 'all') {

            $totalRecords = Customer::select('count(*) as allcount')
                ->count();
            $customers = DB::table('customers')->where('name', 'like', '%' . $searchValue . '%')->orderBy($columnName, $columnSortOrder)->skip($start)->take($row_per_page)->get();
        } elseif ($id == 'due') {

            $totalRecords = Customer::where('due_balance', '>', 0)->select('count(*) as allcount')->where('outlet_id', '=', Auth::user()->outlet_id)->count();
            if (auth()->user()->hasrole(['Super Admin', 'Admin'])) {
                $customers = DB::table('customers')->where('due_balance', '>', 0)->where('name', 'like', '%' . $searchValue . '%')
                    ->orderBy($columnName, $columnSortOrder)
                    ->skip($start)
                    ->take($row_per_page)
                    ->get();
            } else {
                $customers = DB::table('customers')->where('due_balance', '>', 0)->where('outlet_id', '=', Auth::user()->outlet_id)->where('name', 'like', '%' . $searchValue . '%')
                    ->orderBy($columnName, $columnSortOrder)
                    ->skip($start)
                    ->take($row_per_page)
                    ->get();
            }

        } else {
            $totalRecords = Customer::select('count(*) as allcount')->where('outlet_id', '=', Auth::user()->outlet_id)->count();
            $customers = DB::table('customers')->orWhere('outlet_id', '=', $id)->where('name', 'like', '%' . $searchValue . '%')->orderBy($columnName, $columnSortOrder)
                ->skip($start)
                ->take($row_per_page)
                ->get();
        }

        $total_record_switch_filter = $totalRecords;

        // fetch records with search

        $data_arr = array();

        foreach ($customers as $customer) {

            $active = route('customer.active', [$customer->id, 0]);
            $inactive = route('customer.active', [$customer->id, 1]);
            $edit = route('customer.edit', $customer->id);
            $delete = route('customer-delete', $customer->id);
            $pay = route('payDue', $customer->id);
            $duepay = route('customer-due', $customer->id);

            if ($customer->is_active == 1) {
                $active = '<div class="media-body text-end icon-state"><label class="switch"><a href="' . $active . '"><input type="checkbox" checked><span class="switch-state"></span></a></label></div>';
            } elseif ($customer->is_active == 0) {

                $active = '<div class="media-body text-end icon-state"><label class="switch"><a href="' . $inactive . '"><input type="checkbox"><span class="switch-state"></span></a></label></div>';
            }

            $s_no = $customer->id;
            $name = $customer->name;
            $mobile = $customer->mobile;
            $outlet_name = Outlet::getOutletName($customer->outlet_id);
            $points = $customer->points;
            $due = $customer->due_balance;
            $is_active = $active;
            $action = '<div class="btn-group" style="text-align: center"><form action="' . $edit . '" method="GET"><button type="submit" class="btn btn-primary btn-xs open-modal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></form><button type="button" class="btn btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#staticBackdrop' . $s_no . '" ><i class="fa fa-trash"></i></button><div class="modal fade" id="staticBackdrop' . $s_no . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h1 class="modal-title fs-5" id="staticBackdropLabel">Are You Sure want To Delete?</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-footer"><button type="button" class="btn btn-primary btn-xs" data-bs-dismiss="modal">Close</button><form action="' . $delete . '" method="get"><button type="submit" class="btn btn-danger btn-xs">Permanent Delete</button></form></div></div></div></div>';
            $check = Customer::where('id', $customer->id)->first();
            if ($customer->due_balance > 0) {
                $action .= '<a href="' . $duepay . '"class="btn btn-success btn-sm" title="Pay Now"style="margin-right:3px"><i class="fa fa-paypal"></i></a>';
            }

            $data_arr[] = array(
                "id" => $s_no,
                "name" => $name,
                "mobile" => $mobile,
                "outlet_name" => $outlet_name,
                "points" => $points,
                "due" => $due,
                "is_active" => $is_active,
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

    public function active($id, $status)
    {

        $data = Customer::find($id);
        $data->is_active = $status;
        $data->save();
        return redirect()->back()->with('success', 'Active Status Updated');
    }

    public function customerDue($id)
    {
        $customer = Customer::where('id', $id)->first();
        $invoices = OutletInvoice::where('customer_id', $id)->where('due_amount', '>', 0)->get();
        return view('admin.customermanagement.due_management', compact('customer', 'invoices'));
    }

    public function customerDuePayment(Request $request)
    {

        $customer = Customer::where('id', $request->customer_id)->first();
        $invoices = OutletInvoice::where('customer_id', $request->customer_id)->where('due_amount', '>', 0)->orderBy('id', 'asc')->get();

        try {

            $due = array(

                'due_balance' => $customer->due_balance - $request->paid_amount,

            );
            Customer::where('id', $request->customer_id)->update($due);
            $rest_amount = $customer->due_balance - $request->paid_amount;

            CustomerDuePayment::create([
                'outlet_id' => $request->outlet_id,
                'customer_id' => $request->customer_id,
                'due_amount' => $customer->due_balance,
                'pay' => $request->paid_amount,
                'rest_amount' => $rest_amount,
                'received_by' => Auth::user()->id,
            ]);

            $pay = $request->paid_amount;
            foreach ($invoices as $invoice) {

                if ($pay >= 0) {
                    if ($pay == $invoice->due_amount) {
                        $data = array(
                            'due_amount' => $invoice->due_amount - $pay,
                            'paid_amount' => $invoice->paid_amount + $pay,
                        );
                        $pay = 0;
                    } elseif ($pay > $invoice->due_amount) {
                        $payment = $invoice->due_amount;
                        $data = array(
                            'due_amount' => $invoice->due_amount - $payment,
                            'paid_amount' => $payment + $pay,
                        );
                        $pay = $pay - $invoice->due_amount;
                    } else {
                        $data = array(
                            'due_amount' => $invoice->due_amount - $pay,
                            'paid_amount' => $invoice->paid_amount + $pay,
                        );
                        $pay = 0;
                    }
                    OutletInvoice::where('customer_id', $request->customer_id)->where('id', $invoice->id)->update($data);
                } else {
                    break;
                }
            }
            return redirect()->back()->with('success', 'Due Payment Successful.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
