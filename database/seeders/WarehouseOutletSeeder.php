<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Outlet;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseOutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warehouseData = [
            'warehouse_name' => 'Main Warehouse',
            'address' => 'Dhaka',
            'mobile' => '01710000000',
            'is_active' => true,
        ];
        Warehouse::firstOrCreate($warehouseData);

        $outletData = [
            'outlet_name' => 'Moonlight Pharmacy',
            'address' => 'Bogura',
            'mobile' => '01810000000',
            'is_active' => true,
        ];
        Outlet::firstOrCreate($outletData);

        $outlets = Outlet::all();
        foreach ($outlets as $outlet) {
            Customer::firstOrCreate([
                'name' => 'Walking Customer',
                'mobile' => '01700000000',
                'address' => 'N/A',
                'outlet_id' => $outlet->id,
                'points' => 0,
                'is_active' => true,
            ]);
        }
    }
}
