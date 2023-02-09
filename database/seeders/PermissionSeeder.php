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

            'medicine.management',
            'medicine.create',
            'medicine.edit',
            'medicine.delete',
            'supplier.management',
            'supplier.create',
            'supplier.edit',
            'supplier.delete',

            'outlet.management',
            'outlet.create',
            'outlet.edit',
            'outlet.delete',
            'outletStock',
            'warehouseStock',
            'myprofile',
            'warehouse-return.management',
            'warehouse-return.create',
            'warehouse-return.edit',
            'warehouse-return.delete',

            'sent_stock_request',
            'stock_request',


            'customer.management',
            'customer.create',
            'customer.edit',
            'customer.delete',

            'medchine_purchase.management',
            'medchine_purchase.create',
            'medchine_purchase.edit',
            'medchine_purchase.delete',

            'distribute-medicine.management',
            'distribute-medicine.create',
            'distribute-medicine.edit',
            'distribute-medicine.delete',


            'warehouse.management',
            'warehouse.create',
            'warehouse.edit',
            'warehouse.delete',

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

