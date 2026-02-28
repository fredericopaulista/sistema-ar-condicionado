<?php

namespace App\Services;

use PDO;

class PermissionService
{
    private static $permissions = null;

    public static function has($permissionSlug)
    {
        if (!isset($_SESSION['user_id'])) return false;
        
        $userPermissions = self::getUserPermissions($_SESSION['user_id']);
        return in_array($permissionSlug, $userPermissions);
    }

    public static function getUserPermissions($userId)
    {
        if (self::$permissions !== null) return self::$permissions;

        $db = \App\Services\Database::getInstance();
        
        // Get role of user
        $stmt = $db->prepare("SELECT role_id FROM usuarios WHERE id = ?");
        $stmt->execute([$userId]);
        $roleId = $stmt->fetchColumn();

        if (!$roleId) {
            self::$permissions = [];
            return [];
        }

        // Get permissions of role
        $stmt = $db->prepare("
            SELECT p.slug 
            FROM permissoes p
            JOIN role_permissoes rp ON p.id = rp.permissao_id
            WHERE rp.role_id = ?
        ");
        $stmt->execute([$roleId]);
        self::$permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return self::$permissions;
    }
}
