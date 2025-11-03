<?php

namespace App\Constants;

class AppConstant
{
        public const DEFAULT_USER_ROLE = [
            'ADMIN' => 'Admin',
            'BACKEND_DEV' => 'Backend developer',
            'FRONTEND_DEV' => 'Frontend developer',
            'CUSTOMER' => 'Customer',
            'SALE' => 'Sale',
        ];

        const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
         // Status constants
    // public const STATUS_ACTIVE = 'ACTIVE';
    // public const STATUS_INACTIVE = 'INACTIVE';

    // // Type constants
    // public const TYPE_SYSTEM = 'SYSTEM';
    // public const TYPE_CUSTOM = 'CUSTOM';

    // /**
    //  * Check if role is a basic role
    //  */
    // public static function isBasicRole(string $roleName): bool
    // {
    //     return in_array($roleName, [
    //         self::ROLE_CUSTOMER,
    //         self::ROLE_FRONTEND_DEV,
    //     ]);
    // }




}
