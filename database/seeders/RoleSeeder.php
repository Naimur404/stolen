<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Admin', 'Outlet Manager', 'Outlet Sales Man'];

        $admin_permissions = [
            'myprofile',

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

            'stock_request',

            'sale-return.management',
            'sale-return.store',
            'sale-return.show',
            'sale-return.delete',
            'sale-return.detail',

        ];

        $manager_permissions = [
            'myprofile',

            'outletStock',

            'customer.management',
            'customer.create',
            'customer.edit',

            'distribute-medicine.management',
            'distribute-medicine.checkin',

            'invoice.management',
            'invoice.create',
            'invoice.edit',
            'invoice.delete',

            'sent_stock_request',
            'stock_request',

            'sale-return.management',
            'sale-return.store',
            'sale-return.show',
            'sale-return.delete',
            'sale-return.detail',
        ];

        $sales_permissions = [
            'myprofile',

            'outletStock',

            'customer.management',
            'customer.create',
            'customer.edit',

            'invoice.management',
            'invoice.create',
            'invoice.edit',
        ];

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminPerms = Permission::whereIn('name', $admin_permissions)->pluck('id','id');
        $adminRole->syncPermissions($adminPerms);


        $managerRole = Role::firstOrCreate(['name' => 'Outlet Manager']);
        $managerPerms = Permission::whereIn('name', $manager_permissions)->pluck('id','id');
        $managerRole->syncPermissions($managerPerms);

        $posRole = Role::firstOrCreate(['name' => 'Outlet Sales Man']);
        $posPerms = Permission::whereIn('name', $sales_permissions)->pluck('id','id');
        $posRole->syncPermissions($posPerms);


    }
}
