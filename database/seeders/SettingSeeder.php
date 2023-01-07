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
            'address'  => 'House #16, Road #22, Sector #14,
            Uttara Model Town, Dhaka-1230',
            'phone_no' => '+88 01737-569833',
            'description' => 'Pigeon Soft is a full-service visual communications and information technology company located in Bangladesh and serving clients worldwide. We provide Website Design & Development, Software Development, Android and iOS Apps Development, Online Marketing, Technical Support and Consultancy Service.',
            'website' => 'https://pigeon-soft.com/',
            'footer_text' => '© 2016 - 2023 Copyright Pigeon Soft. All Rights Reserved'
        ];
        Settings::create($setting);
    }
}
