<?php

namespace App\Models;

use PDO;

class Configuracao extends BaseModel
{
    protected $table = 'configuracoes_sistema';

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        $rows = $stmt->fetchAll();
        
        $config = [];
        foreach ($rows as $row) {
            $config[$row['chave']] = $row['valor'];
        }
        return $config;
    }

    public function get($chave, $default = null)
    {
        $stmt = $this->db->prepare("SELECT valor FROM {$this->table} WHERE chave = ? LIMIT 1");
        $stmt->execute([$chave]);
        $res = $stmt->fetch();
        return $res ? $res['valor'] : $default;
    }

    public function updateMany($data)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET valor = ? WHERE chave = ?");
        foreach ($data as $chave => $valor) {
            $stmt->execute([$valor, $chave]);
        }
        return true;
    }
}
