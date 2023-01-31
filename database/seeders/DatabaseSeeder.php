<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            UnitSeeder::class,
            MedicineCategorySeeder::class,
            PaymentMethodSeeder::class,
            ManufactureSeeder::class,
            MedicineTypeSeeder::class,
        ]);
    }
}
