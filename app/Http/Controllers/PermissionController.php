<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission as ModelsPermission;
use DataTables;

class PermissionController extends Controller
{
    public function permission(Request $request){
        $permissions = ModelsPermission::all();


        if ($request->ajax()) {
            $data = ModelsPermission::all();
            return  Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $id = $row->id;
                        $edit = route('edit_permission',$id);
                        $delete = route('delete_permission',$id);
                        return view('admin.action.action', compact('edit','delete'));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }


        return view('admin.permission.permission', compact('permissions'));
    }
    public function addPermission(){

        return view('admin.permission.add_permission');
    }

    public function storePermission(Request $request){
        $permission = ModelsPermission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return redirect()->route('permission')->with('success','Permission Add successfully');
    }
    public function editPermission($id){
        $permission = ModelsPermission::findOrFail($id);
        return view('admin.permission.edit_permission', compact('permission'));
    }
    public function updatePermission(Request $request){
       $per_id = $request->id;
       ModelsPermission::findOrFail($per_id)->update([
        'name' => $request->name,
        'guard_name' => $request->guard_name,
       ]);
        return redirect()->route('permission')->with('success','Permission Update successfully');
    }
    public function deletePermission($id){

        ModelsPermission::findOrFail($id)->delete();

         return redirect()->route('permission')->with('success','Permission Delete successfully');
     }
}
