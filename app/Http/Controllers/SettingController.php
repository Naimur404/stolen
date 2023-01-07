<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function setting(){
        $data = Settings::where('id',1)->first();
        return view('admin.setting.setting', compact('data'));

    }
    public function updateSetting(Request $request){



        $data = Settings::where('id',1)->first();
          $data->app_name = $request->app_name;
          $data->address = $request->address;
          $data->phone_no = $request->phone_no;
          $data->description = $request->description;
          $data->website = $request->website;
          $data->footer_text = $request->footer_text;

          //for upload logo

          if($request->hasFile('logo')){

            $request->validate([

                'logo' =>'mimes:png,jpg,jpeg,gif'
             ]);
            //  unlink(public_path('uploads/'.$data->logo));

            $ext = $request->file('logo')->extension();
            $final_name = 'logo'.'.'.$ext;
            $request->file('logo')->move(public_path('uploads'),$final_name);
            $data->logo = $final_name;

            }

            //upload file for favicon

            if($request->hasFile('favicon')){

                $request->validate([

                    'favicon' =>'mimes:png,jpg,jpeg,gif'
                    ]);

                // unlink(public_path('uploads/'.$data->favicon));

                $ext = $request->file('favicon')->extension();
                $final_name = 'favicon'.'.'.$ext;
                $request->file('favicon')->move(public_path('uploads'),$final_name);
                $data->favicon = $final_name;


              }
              $data->update();

              return redirect()->back()->with('success','Update successfully');

    }
}
