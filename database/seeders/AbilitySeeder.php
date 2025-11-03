<?php

namespace Database\Seeders;

use App\Models\Ability;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allActions = [
            'product:view',
            'product:create',
            'product:update',
            'product:delete',

            'brand:view',
            'brand:create',
            'brand:update',
            'brand:delete',

            'category:view',
            'category:create',
            'category:update',
            'category:delete',

            'dashboard:view',

            'user:view',
            'user:create',
            'user:update',
            'user:delete',

            'role:view',
            'role:create',
            'role:update',
            'role:delete',
            'role:set-abilities',

            'ability:view',
            'ability:create',
            'ability:update',
            'ability:delete',

            'order:view',
            'order:create',
            'order:update',
            'order:delete',
            'order:change-status',

            'purchase:view',
            'purchase:create',
            'purchase:update',
            'purchase:delete',
            'purchase:change-status',
        ];

        foreach ($allActions as $action) {
            Ability::firstOrCreate(
                ['action' => $action],  // Find by action
                ['action' => $action]   // Create if not found
            );
        }

        $this->command->info('Abilities seeded successfully!');
    }
}