<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

class TransactionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:transaction.management|transaction.status|transaction.edit|transaction.delete', ['only' => ['index']]);
        // $this->middleware('permission:transaction.create', ['only' => ['create','store']]);
        $this->middleware('permission:transaction.status', ['only' => ['status']]);
        $this->middleware('permission:transaction.delete', ['only' => ['deleteTran']]);
    }
    public function index(Request $request){
        $data = Transaction::all();
        if ($request->ajax()) {
            $datas =  Transaction::orderBy("id","desc")->get();
            return  DataTables::of($datas)
                    ->addIndexColumn()
                    ->addColumn('is_paid', function($row){
                        $active = 'javascript:void(0)';
                        $inactive = route('status_tran',[$row->id,1]);
                        return view('admin.action.active',compact('active','inactive','row'));
                    })

                    // ->addColumn('action', function($row){
                    //     $id = $row->id;
                    //     $edit = 'javascript:void(0)';
                    //     $delete = route('delete_tran',$row->id);
                    //     return view('admin.action.action', compact('id','edit','delete'));
                    // })
                    ->rawColumns(['is_paid'])

                    // ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.transaction.index',compact('data'));
    }
    public function deleteTran($id){
        Transaction::find($id)->delete();
        return redirect()->route('get_tran')->with('success','Delete successfully');
    }
    public function status($id,$status){

        $data = Transaction::find($id);
        if($status == 1){
            $input = array(
             'pay' => $data->amount,
             'due' => 0,
             'date' => Carbon::today(),
             'is_paid' => 1,


            );
            Transaction::where('id',$id)->update($input);
        }
        return redirect()->route('get_tran')->with('success','Payment Is Paid');
    }
}
