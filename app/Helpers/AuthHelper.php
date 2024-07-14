<?php

namespace App\Helpers;

use App\Models\User;

class AuthHelper
{
    public static function user(): User|null
    {
        return request()->user();
    }
}
