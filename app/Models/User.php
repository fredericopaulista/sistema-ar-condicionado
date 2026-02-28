<?php

namespace App\Models;

use PDO;

class User extends BaseModel
{
    protected $table = 'usuarios';

    public function allWithRole()
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nome as role_nome, r.slug as role_slug 
            FROM {$this->table} u 
            LEFT JOIN roles r ON u.role_id = r.id 
            ORDER BY u.nome ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nome as role_nome, r.slug as role_slug 
            FROM {$this->table} u 
            LEFT JOIN roles r ON u.role_id = r.id 
            WHERE u.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (nome, email, senha, role_id) VALUES (:nome, :email, :senha, :role_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':senha' => password_hash($data['senha'], PASSWORD_DEFAULT),
            ':role_id' => $data['role_id'] ?? null
        ]);
    }

    public function update($id, $data)
    {
        $fields = ["nome = :nome", "email = :email", "role_id = :role_id"];
        $params = [
            ':id' => $id,
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':role_id' => $data['role_id']
        ];

        if (!empty($data['senha'])) {
            $fields[] = "senha = :senha";
            $params[':senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getRoles()
    {
        return $this->db->query("SELECT * FROM roles ORDER BY nome ASC")->fetchAll();
    }
}
