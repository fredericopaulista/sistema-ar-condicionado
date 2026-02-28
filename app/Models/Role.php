<?php
namespace App\Models;

use PDO;

class Role extends BaseModel
{
    protected $table = 'roles';

    public function allWithPermissionCount()
    {
        $stmt = $this->db->query("
            SELECT r.*, COUNT(rp.permissao_id) as total_permissoes
            FROM roles r
            LEFT JOIN role_permissoes rp ON r.id = rp.role_id
            GROUP BY r.id
            ORDER BY r.nome ASC
        ");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        $role = $stmt->fetch();

        if ($role) {
            $stmt = $this->db->prepare("SELECT permissao_id FROM role_permissoes WHERE role_id = ?");
            $stmt->execute([$id]);
            $role['permissions'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        return $role;
    }

    public function getAllPermissions()
    {
        return $this->db->query("SELECT * FROM permissoes ORDER BY nome ASC")->fetchAll();
    }

    public function syncPermissions($roleId, $permissionIds)
    {
        // Remove existing
        $stmt = $this->db->prepare("DELETE FROM role_permissoes WHERE role_id = ?");
        $stmt->execute([$roleId]);

        // Add new
        if (!empty($permissionIds)) {
            $sql = "INSERT INTO role_permissoes (role_id, permissao_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            foreach ($permissionIds as $permId) {
                $stmt->execute([$roleId, $permId]);
            }
        }
        return true;
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (nome, slug, descricao) VALUES (:nome, :slug, :descricao)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nome' => $data['nome'],
            ':slug' => $data['slug'],
            ':descricao' => $data['descricao']
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET nome = :nome, slug = :slug, descricao = :descricao WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nome' => $data['nome'],
            ':slug' => $data['slug'],
            ':descricao' => $data['descricao']
        ]);
    }
}
