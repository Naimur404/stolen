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

            'myprofile',

            'administrativearea.management',
            'settings.management',

            'permission.edit',
            'permission.delete',

            'warehouse.management',
            'warehouse.create',
            'warehouse.edit',
            'warehouse.delete',

            'outlet.management',
            'outlet.create',
            'outlet.edit',
            'outlet.delete',

            'manufacturer.management',
            'manufacturer.create',
            'manufacturer.edit',
            'manufacturer.delete',

            'category.manage',
            'category.create',
            'category.edit',
            'category.delete',

            'unit.management',
            'unit.create',
            'unit.edit',
            'unit.delete',

            'medicine.management',
            'medicine.create',
            'medicine.edit',
            'medicine.delete',

            'supplier.management',
            'supplier.create',
            'supplier.edit',
            'supplier.delete',

            'payment-method.management',
            'payment-method.create',
            'payment-method.edit',
            'payment-method.delete',

            'outletStock',
            'warehouseStock',

            'warehouse-return.management',
            'warehouse-return.create',
            'warehouse-return.edit',
            'warehouse-return.delete',

            'sale-return.management',
            'sale-return.store',
            'sale-return.show',
            'sale-return.delete',
            'sale-return.detail',

            'customer.management',
            'customer.create',
            'customer.edit',
            'customer.delete',

            'medchine_purchase.management',
            'medchine_purchase.create',
            'medchine_purchase.edit',
            'medchine_purchase.delete',
            'medchine_purchase.checkin',

            'distribute-medicine.management',
            'distribute-medicine.create',
            'distribute-medicine.edit',
            'distribute-medicine.delete',
            'distribute-medicine.checkin',

            'invoice.management',
            'invoice.create',
            'invoice.edit',
            'invoice.delete',

            'sent_stock_request',
            'stock_request',


        ];

        foreach ($permissions as $permission)

            if (Permission::where('name', $permission)->exists()) {
                continue;
            }else{
                Permission::create(['name' => $permission]);
            }

        }
    }

