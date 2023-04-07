<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Outlet;
use App\Models\OutletInvoice;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CustomerManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
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
        //
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
     * @param  \Illuminate\Http\Request  $request
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
     * @param  \App\Models\Customer  $customerManagement
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customerManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customerManagement
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customerManagement
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
     * @param  \App\Models\Customer  $customerManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Customer::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been Deleted.');
    }
    public function customer(Request $request, $id)

    {
        if ($id != 'all') {
            if ($request->ajax()) {

                $data = Customer::where("outlet_id", $id)->get();
                return  DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('address', function ($row) {
                        $address = Str::limit($row->address, 15);
                        return $address;
                    })
                    ->addColumn('outlet_name', function ($row) {
                        $id = $row->outlet_id;
                        return view('admin.action.outlet', compact('id'));
                    })
                    ->addColumn('is_active', function ($row) {
                        $active = route('customer.active', [$row->id, 0]);
                        $inactive = route('customer.active', [$row->id, 1]);
                        return view('admin.action.active', compact('active', 'inactive', 'row'));
                    })
                    ->addColumn('action', function ($row) {
                        $id = $row->id;

                        $edit = route('customer.edit', $id);
                        $delete = route('customer.destroy', $id);

                        // $permission = 'customer.edit';
                        // $permissiondelete = 'customer.delete';
                        return view('admin.action.customeraction', compact('id', 'edit', 'delete', 'pay'));
                    })
                    ->rawColumns(['address'])
                    ->rawColumns(['outlet_name'])

                    ->rawColumns(['is_active'])
                    ->rawColumns(['action'])
                    ->make(true);
            }
            $outlet = Outlet::where('id', Auth::user()->outlet_id)->pluck('outlet_name', 'id');
        } else {
            if ($request->ajax()) {
                $data = Customer::all();
                return  DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('address', function ($row) {
                        $address = Str::limit($row->address, 15);
                        return $address;
                    })
                    ->addColumn('outlet_name', function ($row) {
                        $id = $row->outlet_id;
                        return view('admin.action.outlet', compact('id'));
                    })
                    ->addColumn('is_active', function ($row) {
                        $active = route('customer.active', [$row->id, 0]);
                        $inactive = route('customer.active', [$row->id, 1]);
                        return view('admin.action.active', compact('active', 'inactive', 'row'));
                    })
                    ->addColumn('action', function ($row) {
                        $id = $row->id;

                        $edit = route('customer.edit', $id);
                        $delete = route('customer.destroy', $id);
                        $pay =  route('payDue', $id);
                        // $permission = 'customer.edit';
                        // $permissiondelete = 'customer.delete';
                        return view('admin.action.customeraction', compact('id', 'edit', 'delete', 'pay'));
                    })
                    ->rawColumns(['address'])
                    ->rawColumns(['outlet_name'])

                    ->rawColumns(['is_active'])
                    ->rawColumns(['action'])
                    ->make(true);
            }
            $outlet = Outlet::pluck('outlet_name', 'id');

            $outlet = new Collection($outlet);
            $outlet->prepend('All Outlet Customer', 'all');
        }





        return view('admin.customermanagement.index', compact('outlet'));
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
        // dump($invoices);

        try {


            $due = array(

                'due_balance' => $customer->due_balance - $request->paid_amount

            );
            Customer::where('id', $request->customer_id)->update($due);
            $pay = $request->paid_amount;
            foreach ($invoices as $invoice) {


                if ($pay >= 0) {
                    if($pay == $invoice->due_amount){
                        $data = array(
                            'due_amount' => $invoice->due_amount - $pay,
                            'paid_amount' => $invoice->paid_amount + $pay,
                        );
                        $pay = 0;
                    }elseif($pay > $invoice->due_amount){
                          $payment =  $invoice->due_amount;
                          $data = array(
                            'due_amount' => $invoice->due_amount - $payment,
                            'paid_amount' =>  $payment + $pay,
                        );
                        $pay = $pay - $invoice->due_amount;
                    }else{
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
