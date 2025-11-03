<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Constants\AppConstant;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => AppConstant::DEFAULT_USER_ROLE['ADMIN'],
            'description' => 'Administrator User',
            'type' => 'default',
        ]);

        Role::create([
            'name' => AppConstant::DEFAULT_USER_ROLE['BACKEND_DEV'],
            'description' => 'Backend Developer User',
            'type' => 'default',
        ]);

        Role::create([
            'name' => AppConstant::DEFAULT_USER_ROLE['FRONTEND_DEV'],
            'description' => 'Frontend Developer User',
            'type' => 'default',
        ]);

        Role::create([
            'name' => AppConstant::DEFAULT_USER_ROLE['CUSTOMER'],
            'description' => 'Customer User',
            'type' => 'default',
        ]);

        Role::create([
            'name' => AppConstant::DEFAULT_USER_ROLE['SALE'],
            'description' => 'Sale User',
            'type' => 'default',
        ]);
    }
}