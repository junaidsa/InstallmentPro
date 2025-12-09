<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class PermissionHelper
{
    public static function hasPermission($permissionName)
    {
        $user = Session::get('user');

        foreach ($user->permissions as $permission) {
            if ($permission['name'] === $permissionName) {
                return true;
            }
        }

        return false;
    }
}
