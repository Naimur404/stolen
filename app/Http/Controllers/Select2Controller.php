<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Supplier;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class Select2Controller extends Controller
{
    public function getCategory(Request $request){
        $search = $request->search;

        if($search == ''){
           $categorys = Category::orderby('category_name','asc')->select('id','category_name')->limit(5)->get();
        }else{
           $categorys = Category::orderby('category_name','asc')->select('id','category_name')->where('category_name', 'like', '%' .$search . '%')->limit(5)->get();
        }

        $response = array();
        foreach($categorys as $category){
           $response[] = array(
                "id"=>$category->id,
                "text"=>$category->category_name
           );
        }
        return response()->json($response);
     }
     public function select_manufacturer(Request $request){

        $search = $request->search;

          if($search == ''){
             $categories = Manufacturer::orderby('id','asc')->select('id','manufacturer_name')->get();
          }else{
             $categories = Manufacturer::orderby('id','asc')->select('id','manufacturer_name')->where('manufacturer_name', 'like', '%' .$search . '%')->get();
          }

          $response = array();
          foreach($categories as $category){
             $response[] = array(
                  "id"=>$category->id,
                  "text"=>$category->manufacturer_name
             );
          }

          return response()->json($response);
      }


     public function get_supplier(Request $request){

        $search = $request->search;

          if($search == ''){
             $suppliers = Supplier::orderby('id','asc')->select('id','supplier_name')
             ->get();
          }else{
             $suppliers = Supplier::orderby('id','asc')->select('id','supplier_name')
             ->where('supplier_name', 'like', '%' .$search . '%')
             ->get();
          }

          $response = array();
          foreach($suppliers as $supplier){
             $response[] = array(
                  "id"=>$supplier->id,
                  "text"=>$supplier->supplier_name
             );
          }

          return response()->json($response);
      }

      public function getType(Request $request){
        $search = $request->search;

        if($search == ''){
            $types = Type::orderby('type_name','asc')->select('id','type_name')->limit(5)->get();
        }else{
           $types = Type::orderby('type_name','asc')->select('id','type_name')->where('type_name', 'like', '%' .$search . '%')->limit(5)->get();
        }

        $response = array();
        foreach($types as $type){
           $response[] = array(
                "id"=>$type->id,
                "text"=>$type->type_name
           );
        }
        return response()->json($response);
     }
     public function getUnit(Request $request){
        $search = $request->search;

        if($search == ''){
           $units = Unit::orderby('unit_name','asc')->select('id','unit_name')->limit(5)->get();
        }else{
           $units = Unit::orderby('unit_name','asc')->select('id','unit_name')->where('unit_name', 'like', '%' .$search . '%')->limit(5)->get();
        }

        $response = array();
        foreach($units as $unit){
           $response[] = array(
                "id"=>$unit->id,
                "text"=>$unit->unit_name
           );
        }
        return response()->json($response);
     }
     public function getManufacturer(Request $request){
        $search = $request->search;

        if($search == ''){
            $Manufacturers = Manufacturer::orderby('manufacturer_name','asc')->select('id','manufacturer_name')->limit(5)->get();
        }else{
           $Manufacturers = Manufacturer::orderby('manufacturer_name','asc')->select('id','manufacturer_name')->where('manufacturer_name', 'like', '%' .$search . '%')->limit(5)->get();
        }

        $response = array();
        foreach($Manufacturers as $Manufacturer){
           $response[] = array(
                "id"=>$Manufacturer->id,
                "text"=>$Manufacturer->manufacturer_name
           );
        }
        return response()->json($response);
     }
}
