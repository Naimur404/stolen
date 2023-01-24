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
            'logo' => 'logo.png',
            'favicon' => 'favicon.png',
            'app_name' => 'Pigeon Soft',
            'address' => 'Dhaka, Bangladesh',
            'phone_no' => '+88 01737-569833',
            'description' => 'Pigeon Soft is a full-service visual communications and information technology company located in Bangladesh',
            'website' => 'https://pigeon-soft.com/',
            'footer_text' => '© 2016 - 2023 Copyright Pigeon Soft. All Rights Reserved'
        ];
        $settings = Settings::all()->first();
        if (is_null($settings)) {
            Settings::create($setting);
        }

    }
}
