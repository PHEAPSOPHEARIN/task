<?php

namespace Database\Seeders;

use App\Constants\AppConstant;
use App\Models\Ability;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $abilities = Ability::all();
        $roles = Role::all();

        foreach ($roles as $role) {
            foreach ($abilities as $ability) {
                [$resource, $action] = explode(':', $ability->action);
                if ($role->name == AppConstant::DEFAULT_USER_ROLE['ADMIN']) {
                    $role->abilities()->attach($ability->id);
                }
                if ($role->name == AppConstant::DEFAULT_USER_ROLE['BACKEND_DEV']) {
                    $role->abilities()->attach($ability->id);
                }
                if ($role->name == AppConstant::DEFAULT_USER_ROLE['FRONTEND_DEV']) {
                    if (
                        ($action == 'view' && in_array($resource, ['product', 'brand', 'category'])) ||
                        ($resource == 'user' && $action == 'create')
                    ) {
                        $role->abilities()->attach($ability->id);
                    }
                }
                if ($role->name == AppConstant::DEFAULT_USER_ROLE['CUSTOMER']) {
                    if (
                        ($action == 'view' && in_array($resource, ['product', 'brand', 'category'])) ||
                        ($resource == 'user' && $action == 'update') ||
                        ($resource == 'order' && $action == 'create')
                    ) {
                        $role->abilities()->attach($ability->id);
                    }
                }
            }
        }
    }
}