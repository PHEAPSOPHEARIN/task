<?php

namespace Database\Seeders;

use App\Constants\AppConstant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', AppConstant::DEFAULT_USER_ROLE['ADMIN'])->first();
        $backendRole = Role::where('name', AppConstant::DEFAULT_USER_ROLE['BACKEND_DEV'])->first();
        $frontendRole = Role::where('name', AppConstant::DEFAULT_USER_ROLE['FRONTEND_DEV'])->first();
        $cumstoerRole = Role::where('name', AppConstant::DEFAULT_USER_ROLE['CUSTOMER'])->first();
        $saleRole = Role::where('name', AppConstant::DEFAULT_USER_ROLE['SALE'])->first();
        User::create([
            'name' => 'Admin',
            'email' => 'admin1@test.com',
            'password' => '123456',
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Backend',
            'email' => 'backend1@test.com',
            'password' => '123456',
            'role_id' => $backendRole->id,
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Frontend',
            'email' => 'front-end1@test.com',
            'password' => '123456',
            'role_id' => $frontendRole->id,
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Customer',
            'email' => 'customer1@test.com',
            'password' => '123456',
            'role_id' => $cumstoerRole->id,
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Sale 1',
            'email' => 'sale1@test.com',
            'password' => '123456',
            'role_id' => $saleRole->id,
            'email_verified_at' => now(),
        ]);
    }
}
