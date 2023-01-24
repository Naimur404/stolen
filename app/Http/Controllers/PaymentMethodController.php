<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:payment-method.management|payment-method.create|payment-method.edit|payment-method.delete', ['only' => ['index','store']]);
        $this->middleware('permission:payment-method.create', ['only' => ['create','store']]);
        $this->middleware('permission:payment-method.edit', ['only' => ['edit','update']]);
        $this->middleware('permission:payment-method.delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = PaymentMethod::orderBy("id","desc")->get();
            return  DataTables::of($data)
                    ->addIndexColumn()
                        ->addColumn('action', function($row){
                        $id = $row->id;

                        $edit = route('payment-method.edit',$id);
                        $delete = route('payment-method.destroy',$id);
                        $permission = 'payment-method.edit';
                        $permissiondelete = 'payment-method.delete';
                        return view('admin.action.action', compact('id','edit','delete','permission','permissiondelete'));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.payment.payment');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'method_name' => 'required|string',


        ]);
        $method_name = PaymentMethod::create($request->all());
         return response()->json($method_name);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        $method_name =PaymentMethod::find($paymentMethod->id);
        return response()->json($method_name);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'method_name' => 'required|string',


        ]);
            $input = $request->all();

            $method_name = $paymentMethod->update($input);
            return response()->json($method_name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()->route('payment-method.index')->with('success','Delete successfully');
    }
}
