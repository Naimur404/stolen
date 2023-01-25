<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'category.manage',
            'category.create',
            'category.edit',
            'category.delete',
            'unit.management',
            'unit.create',
            'unit.edit',
            'unit.delete',
            'administrativearea.management',
            'settings.management',
            'payment-method.management',
            'payment-method.create',
            'payment-method.edit',
            'payment-method.delete',
            'permission.edit',
            'permission.delete',
            'payment_method.manage',
            'payment_method.create',
            'payment_method.edit',
            'payment_method.delete',
            'manufacturer.management',
            'manufacturer.create',
            'manufacturer.edit',
            'manufacturer.delete',
        ];

        foreach ($permissions as $permission)

            if (Permission::where('name', $permission)->exists()) {
                continue;
            }else{
                Permission::create(['name' => $permission]);
            }

        }
    }

