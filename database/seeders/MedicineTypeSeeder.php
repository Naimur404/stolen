<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = array(
            'Medicine',
            'Non-Medicine',
        );

        foreach ($types as $type){
            Type::firstOrCreate([
                'type_name' => $type
            ]);
        }
    }
}
