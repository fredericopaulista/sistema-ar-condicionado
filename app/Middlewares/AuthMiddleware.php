<?php

namespace App\Middlewares;

class AuthMiddleware
{
    public static function check()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public static function permission($slug)
    {
        self::check();
        if (!\App\Services\PermissionService::has($slug)) {
            header('Location: /dashboard?error=unauthorized');
            exit;
        }
    }
}
