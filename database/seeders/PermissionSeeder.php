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
            'outletStock-price-update',
            'outletStock-price-edit',
            'warehouseStock',
            'warehouseStock-price-update',
            'warehousetStock-price-edit',

            'expiry-wise-report-outlet',
            'expiry-wise-report-outlet.submit',

            'expiry-wise-report-warehouse',
            'expiry-wise-report-warehouse.submit',

            'sale.report',
            'purchase.report',
            'sale_report.search',
            'purchase_report.search',

            'sale_report_by_payment',
            'sale_report_by_payment.search',

            'best_selling',
            'best_selling.search',

            'slow_selling',
            'slow_selling.search',

            'sale_report_details',
            'sale_report_details.search',

            'sale_report_by_user',
            'sale_report_by_user.search',

            'sale_report.search',
            'purchase_report.search',

            'writeoff',
            'outlet-writeoff',
            'warehouse-writeoff',
            'summary',
            'outlet-summary',
            'warehouse-summary',

            'customer-due',
            'customer-due-payment',

            'supplier-due',
            'supplier-due-payment',

            'distribute_medicine_report_for_outlet',
            'distribute_medicine_report_for_outlet.search',

            'distribute_medicine_report_for_warehouse',
            'distribute_medicine_report_for_warehouse.search',

             'stock_request_report_for_outlet',
            'stock_request_report_for_outlet.search',

            'stock_request_report_for_warehouse',
            'stock_request_report_for_warehouse.search',

             'return_medicine_report_for_outlet',
            'return_medicine_report_for_outlet.search',

            'return_medicine_report_for_warehouse',
            'return_medicine_report_for_warehouse.search',

            'sales-details',

            'sale_return_report',
            'sale_return_report.search',

            'outlet-stock.report',
            'outlet-stock.search',
            'warehouse-stock.report',
            'warehouse-stock.search',
            'pay-due.payment',

            'category-wise-report-outlet',
            'category-wise-report-warehouse',
            'category-wise-report',

            'category-wise-report-alert-outlet',
            'category-wise-report-alert-warehouse',
            'category-wise-report-alert-submit',
            'category-wise-report-alert',
            'supplier_wise_sale_report',
            'supplier_wise_sale_report.search',

            'supplier_wise_stock_report_outlet',
            'supplier_wise_stock_report_outlet.search',
            'supplier_wise_stock_report_warehouse',
            'supplier_wise_stock_report_warehouse.search',

            'manufacturer_wise_stock_report_outlet',
            'manufacturer_wise_stock_report_outlet.search',
            'manufacturer_wise_stock_report_warehouse',
            'manufacturer_wise_stock_report_warehouse.search',

            'profit_loss',
            'profit_loss.report',

            'warehouse-return.management',
            'warehouse-return.create',
            'warehouse-return.edit',
            'warehouse-return.delete',
            'warehouse-return.checkIn',

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
            'medicine-sale.report',
            'medicine-sale-submit.report',

            'not-sold-medicine.report',
            'not-sold-medicine-submit.report',
            'redeem_point_report',
            'redeem_point_report_submit',
            'warehouse_value',
            'outlet_value',
            'due-payment-report',
            'due-payment-report-submit',
            'manufacture_sale_report',
            'manufacture_sale_report_submit'




        ];

        foreach ($permissions as $permission)

            if (Permission::where('name', $permission)->exists()) {
                continue;
            }else{
                Permission::create(['name' => $permission]);
            }

        }
    }

