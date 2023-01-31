<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Medicine;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $csvFile = fopen(base_path("database/data/medicine_dataset.csv"), "r");

        $limit = 2000;
        $c=0;
        $firstline = true;
        while (($data = fgetcsv($csvFile, 3500, ",")) !== FALSE && $c < $limit) {
            $exists = Medicine::where('medicine_name', '=' , $data[1])->where('strength', '=', $data[3])->exists();
            if (!$firstline && !$exists) {
                Medicine::create([
                    'manufacturer_id' => Manufacturer::where('manufacturer_name', '=', $data[0])->value('id') ?? 1,
                    'medicine_name' => $data[1],
                    'generic_name' => $data[2],
                    'strength' => $data[3],
                    'category_id' => Category::where('category_name', '=', $data[4])->value('id') ?? 1,
                    'price' => (float) $data[5],
                    'unit_id' => Unit::where('unit_name', '=', 'pc')->value('id') ?? 1,
                    'type_id' => Type::where('type_name', '=', 'Medicine')->value('id') ?? 1,
                ]);
                $c++;
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
