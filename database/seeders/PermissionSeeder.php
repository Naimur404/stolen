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
            'payment_method.manage',
            'employee.management',
            'payment_method.create',
            'payment_method.edit',
            'payment_method.delete',
            'employee.create',
            'employee.edit',
            'employee.delete',
            'healthOrganization.management',
            'healthOrganization.create',
            'healthOrganization.edit',
            'healthOrganization.delete',
            'transaction.management',
            'card_holder.management',
            'card_holder.create',
            'card_holder.edit',
            'card_holder.delete',
            'administrativearea.management',
            'HealthService_Log.management',
            'customer_lead.management',
            'customer_lead.create',
            'customer_lead.edit',
            'customer_lead.delete',
            'healthCardPackage.management',
            'healthCardPackage.create',
            'healthCardPackage.edit',
            'healthCardPackage.delete',
            'transaction.delete',
            'transaction.status',
            'settings.management',
           'HealthService_Log.search',
            'HealthService_Log.edit',
            'HealthService_Log.delete',
        ];

        foreach ($permissions as $permission)

            if (Permission::where('name', $permission)->exists()) {
                continue;
            }else{
                Permission::create(['name' => $permission]);
            }

        }
    }

