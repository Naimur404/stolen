<?php

namespace App\Http\Controllers;

use App\Models\CustomerManagement;
use App\Models\Outlet;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
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
         $this->middleware('permission:customer.management|customer.create|customer.edit|customer.delete', ['only' => ['customer','store']]);
         $this->middleware('permission:customer.create', ['only' => ['create','store']]);
         $this->middleware('permission:customer.edit', ['only' => ['edit','update']]);
         $this->middleware('permission:customer.delete', ['only' => ['destroy']]);
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
           try{
            CustomerManagement::create($input);
            return redirect()->back()->with('success', ' New Customer Added');

           }catch(Exception $e){
            return redirect()->back()->with('success', $e->getMessage());
           }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerManagement  $customerManagement
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerManagement $customerManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerManagement  $customerManagement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {    $customerManagement = CustomerManagement::find($id);
        return view('admin.customermanagement.edit',compact('customerManagement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerManagement  $customerManagement
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


           try{
            CustomerManagement::where('id',$request->id)->update($data);
            return redirect()->back()->with('success', 'Customer Update');

           }catch(Exception $e){
            return redirect()->back()->with('success', $e->getMessage());
           }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerManagement  $customerManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        CustomerManagement::where('id',$id)->delete();
         return redirect()->back()->with('success', 'Data has been Deleted.');
    }
    public function customer(Request $request, $id )

    {
        if($id != 'all'){
            if ($request->ajax()) {
                $data = CustomerManagement::where("outlet_id",$id)->get();
                return  DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('address', function($row){
                            $address = Str::limit($row->address, 15);
                            return $address;

                        })
                        ->addColumn('outlet_name', function($row){
                            $id = $row->outlet_id;
                            return view('admin.action.outlet', compact('id'));

                        })
                        ->addColumn('is_active', function($row){
                            $active = route('customer.active',[$row->id,0]);
                            $inactive = route('customer.active',[$row->id,1]);
                            return view('admin.action.active',compact('active','inactive','row'));

                        })
                        ->addColumn('action', function($row){
                            $id = $row->id;

                            $edit = route('customer.edit',$id);
                            $delete = route('customer.destroy',$id);
                            // $permission = 'customer.edit';
                            // $permissiondelete = 'customer.delete';
                            return view('admin.action.customeraction', compact('id','edit','delete'));
                        })
                        ->rawColumns(['address'])
                        ->rawColumns(['outlet_name'])

                        ->rawColumns(['is_active'])
                        ->rawColumns(['action'])
                        ->make(true);
            }
        }else{
            if ($request->ajax()) {
                $data = CustomerManagement::all();
                return  DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('address', function($row){
                    $address = Str::limit($row->address, 15);
                    return $address;

                })
                ->addColumn('outlet_name', function($row){
                    $id = $row->outlet_id;
                    return view('admin.action.outlet', compact('id'));

                })
                ->addColumn('is_active', function($row){
                    $active = route('customer.active',[$row->id,0]);
                    $inactive = route('customer.active',[$row->id,1]);
                    return view('admin.action.active',compact('active','inactive','row'));

                })
                ->addColumn('action', function($row){
                    $id = $row->id;

                    $edit = route('customer.edit',$id);
                    $delete = route('customer.destroy',$id);
                    // $permission = 'customer.edit';
                    // $permissiondelete = 'customer.delete';
                    return view('admin.action.customeraction', compact('id','edit','delete'));
                })
                ->rawColumns(['address'])
                ->rawColumns(['outlet_name'])

                ->rawColumns(['is_active'])
                ->rawColumns(['action'])
                ->make(true);
            }
        }


        $outlet = Outlet::pluck('outlet_name', 'id');

      $outlet = new Collection($outlet);
      $outlet->prepend('All Outlet Customer', 'all');


        return view('admin.customermanagement.index',compact('outlet'));
    }
    public function active($id,$status){

        $data = CustomerManagement::find($id);
        $data->is_active = $status;
        $data->save();
        return redirect()->back()->with('success','Active Status Updated');

    }
}

