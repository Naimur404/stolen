<?php

namespace Database\Seeders;

use App\Models\Outlet;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin Seeder
        $adminExists = User::where('email', '=', 'admin@admin.com')->exists();
        $adminRole = Role::where(['name' => 'Admin'])->first();

        if (!$adminExists && $adminRole){
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'warehouse_id' => Warehouse::orderBy('id', 'desc')->first()->id
            ]);

            $user->assignRole([$adminRole->id]);
        }

        // Outlet Manager Seeder

        $managerExists = User::where('email', '=', 'manager@gmail.com')->exists();
        $managerRole = Role::where(['name' => 'Outlet Manager'])->first();

        if (!$managerExists && $managerRole){
            $user = User::create([
                'name' => 'Manager',
                'email' => 'manager@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'outlet_id' => Outlet::orderBy('id', 'desc')->first()->id
            ]);

            $user->assignRole([$managerRole->id]);
        }

        // Outlet Sales Man Seeder
        $salesExists = User::where('email', '=', 'pos@gmail.com')->exists();
        $salesRole = Role::where(['name' => 'Outlet Sales Man'])->first();

        if (!$salesExists && $salesRole){
            $user = User::create([
                'name' => 'POS',
                'email' => 'pos@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'),
                'outlet_id' => Outlet::orderBy('id', 'desc')->first()->id
            ]);

            $user->assignRole([$salesRole->id]);
        }
    }
}
