<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = array(
            'pc',
            'ml',
            'gm',
            'KG'
        );

        foreach ($units as $unit){
            Unit::firstOrCreate([
                'unit_name' => $unit
            ]);
        }

    }
}
