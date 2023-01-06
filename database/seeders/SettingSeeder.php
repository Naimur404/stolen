<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = [
            'logo'  => '8584498899.png',
            'favicon' => '7895656.png',
            'app_name' => 'Pigeon Soft',
            'address'  => 'Halishahar, Chittagong',
            'phone_no' => '0156486246',
            'website' => 'www.facebook.com',
            'footer_text' => 'Copyright 2022 Pigeon Soft'
        ];
        Settings::create($setting);
    }
}
