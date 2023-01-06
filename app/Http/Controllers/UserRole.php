<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role as ModelsRole;
use DataTables;

class UserRole extends Controller
{
    public function users(Request $request){
        $users = User::get();
        if ($request->ajax()) {
            $data = User::all();
            return  Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('type', function($row){
                        return view('admin.action.type',compact('row'));

                 })
                    ->addColumn('action', function($row){

                           $btn = '<a href="" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editBook">Edit</a>';

                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteBook">Delete</a>';

                            return $btn;
                    })
                    ->rawColumns(['type'])
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('admin.user_role.user_role', compact('users'));
    }
    public function addUsers(){
        $roles = ModelsRole::get();
        return view('admin.user_role.add_user',compact('roles'));
    }
    public function addUsersStore(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'role' => 'required'
           ]);
           $q = DB::select("SHOW TABLE STATUS LIKE 'users'");
           $user_id = $q[0]->Auto_increment;
           $user = new User();
           $user->name = $request->name;
           $user->email = $request->email;
           $user->password = Hash::make($request->password);
           $user->save();
           $user = User::findOrFail($user_id);
           $user->assignRole($request->role);
           return redirect()->route('user')->with('success','Add New User successfully');

    }
}
