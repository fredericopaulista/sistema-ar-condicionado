<?php

namespace App\Models;

class User extends BaseModel
{
    protected $table = 'usuarios';

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nome, email, senha, nivel) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['nome'],
            $data['email'],
            password_hash($data['senha'], PASSWORD_BCRYPT),
            $data['nivel'] ?? 'admin'
        ]);
    }
}
