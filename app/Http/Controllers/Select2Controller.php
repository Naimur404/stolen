<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Manufacturer;
use App\Models\Medicine;
use App\Models\Outlet;
use App\Models\OutletHasUser;
use App\Models\PaymentMethod;
use App\Models\Supplier;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     public function getOutlet(Request $request){
        $search = $request->search;

        if($search == ''){
            $outlets = Outlet::where('is_active',1)->orderby('outlet_name','asc')->select('id','outlet_name')->limit(5)->get();
        }else{
           $outlets = Outlet::where('is_active',1)->orderby('outlet_name','asc')->select('id','outlet_name')->where('outlet_name', 'like', '%' .$search . '%')->limit(5)->get();
        }

        $response = array();
        foreach($outlets as $outlet){
           $response[] = array(
                "id"=>$outlet->id,
                "text"=>$outlet->outlet_name
           );
        }
        return response()->json($response);
     }
     public function get_all_medicine(Request $request){

        $search = $request->search;
          if($search == ''){

             $medicines = Medicine::orderby('id','asc')

             ->select('id','medicine_name')
             ->get();
          }else{

             $medicines = Medicine::orderby('id','asc')

             ->select('id','medicine_name')
             ->where('medicine_name', 'like', '%' .$search . '%')
             ->get();
          }

          $response = array();
          foreach($medicines as $medicine){
             $response[] = array(
                  "id"=>$medicine->id,
                  "text"=>$medicine->medicine_name
             );
          }

          return response()->json($response);
      }


  public function get_user(Request $request){
    $outlet_id = OutletHasUser::where('user_id',Auth::user()->id)->first();
    $search = $request->search;
    if($search == ''){
      $customers = Customer::where('outlet_id',$outlet_id->outlet_id)->get();
    }else{
        $customers = Customer::where('outlet_id',$outlet_id->outlet_id)->where('mobile', 'like', '%' .$search . '%')->get();

    }
    $response = array();
         foreach($customers as $customers){
            $response[] = array(
                 "id"=>$customers->mobile,
                 "text"=>$customers->mobile,
            );
         }
         return response()->json($response);
  }
  public function get_user_details($id){
            $customer = Customer::where('mobile',$id)->first();

            return json_encode($customer);

  }
  public function get_payment(Request $request){

    $search = $request->search;
      if($search == ''){

         $payments = PaymentMethod::orderby('id','asc')

         ->select('id','method_name')
         ->get();
      }else{

         $payments = PaymentMethod::orderby('id','asc')

         ->select('id','method_name')
         ->where('method_name', 'like', '%' .$search . '%')
         ->get();
      }

      $response = array();
      foreach($payments as $payment){
         $response[] = array(
              "id"=>$payment->id,
              "text"=>$payment->method_name
         );
      }

      return response()->json($response);
  }

}
