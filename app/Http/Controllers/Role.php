<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role as ModelsRole;
use Spatie\Permission\Models\Permission as ModelsPermission;
use DataTables;
class Role extends Controller
{
    public function role(Request $request){
        $roles = ModelsRole::all();


        if ($request->ajax()) {
            $data = ModelsRole::all();
            return  Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $id = $row->id;
                        $edit = route('edit_role',$id);
                        $delete = route('delete_role',$id);
                        return view('admin.action.action', compact('edit','delete'));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }


        return view('admin.role.role', compact('roles'));
    }
    public function addRole(){

        return view('admin.role.add_role');
    }

    public function storeRole(Request $request){
         ModelsRole::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return redirect()->route('role');
    }
    public function editRole($id){
        $role = ModelsRole::findOrFail($id);
        return view('admin.role.edit_role', compact('role'));
    }
    public function updateRole(Request $request){
       $per_id = $request->id;
       ModelsRole::findOrFail($per_id)->update([
        'name' => $request->name,
        'guard_name' => $request->guard_name,
       ]);
        return redirect()->route('role');
    }
    public function deleteRole($id){

        ModelsRole::findOrFail($id)->delete();

         return redirect()->route('role');
     }
     public function getRole(Request $request){
        $search = $request->search;

        if($search == ''){
           $employees = ModelsRole::orderby('name','asc')->select('id','name')->limit(5)->get();
        }else{
           $employees = ModelsRole::orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->limit(5)->get();
        }

        $response = array();
        foreach($employees as $employee){
           $response[] = array(
                "id"=>$employee->id,
                "text"=>$employee->name
           );
        }
        return response()->json($response);
     }

     public function addRolePermission(){

        $permissions = ModelsPermission::all();
        return view('admin.role_in_per.add_role_permi',compact('permissions'));
     }
}
