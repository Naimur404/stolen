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

            'warehouse-stock.report',
            'warehouse-stock.search',

            'sale-return.management',
            'sale-return.store',
            'sale-return.show',
            'sale-return.delete',
            'sale-return.detail',

            'purchase.report',
          'purchase_report.search',
          'distribute_medicine_report_for_warehouse',
            'distribute_medicine_report_for_warehouse.search',
            'stock_request_report_for_warehouse',
            'stock_request_report_for_warehouse.search',
            'return_medicine_report_for_warehouse',
            'return_medicine_report_for_warehouse.search',
            'category-wise-report-warehouse',
            'category-wise-report',
            'category-wise-report-alert-warehouse',
            'category-wise-report-alert-submit',
            'category-wise-report-alert',
            'supplier_wise_stock_report_outlet_warehouse',
            'supplier_wise_stock_report_outlet_warehouse.search',

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

            'sale_report_by_payment',
            'sale_report_by_payment.search',

            'sale_report_details',
            'sale_report_details.search',

            'sale_report_by_user',
            'sale_report_by_user.search',
            'outlet-stock.report',
            'outlet-stock.search',
            'sale.report',
            'sale_report.search',
            'distribute_medicine_report_for_outlet',
            'distribute_medicine_report_for_outlet.search',
            'stock_request_report_for_outlet',
            'stock_request_report_for_outlet.search',
            'return_medicine_report_for_outlet',
            'return_medicine_report_for_outlet.search',
            'sale_return_report',
            'sale_return_report.search',
            'supplier_wise_sale_report',
            'supplier_wise_sale_report.search',
            'sales-details',
            'category-wise-report-outlet',
            'category-wise-report',
            'category-wise-report-alert-outlet',
            'category-wise-report-alert-submit',
            'category-wise-report-alert',
            'supplier_wise_sale_report',
            'supplier_wise_sale_report.search',
            'supplier_wise_stock_report_outlet',
            'supplier_wise_stock_report_outlet.search'

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
