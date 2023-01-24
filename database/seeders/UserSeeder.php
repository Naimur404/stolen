<?php

namespace Database\Seeders;

use App\Models\User;
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

        $userExists = User::where('email', '=', 'admin@admin.com')->exists();
        $role = Role::firstOrCreate(['name' => 'Super Admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        if (!$userExists) {
            $user = User::create([
                'name' => 'Ariful Islam',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678')
            ]);

            $user->assignRole([$role->id]);
        }

    }
}
