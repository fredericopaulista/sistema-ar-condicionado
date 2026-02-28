<?php

namespace App\Models;

use PDO;

class Configuracao extends BaseModel
{
    protected $table = 'configuracoes_sistema';
    protected $sensitiveKeys = ['mail_pass', 'assinafy_api_key'];

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        $rows = $stmt->fetchAll();
        
        $config = [];
        foreach ($rows as $row) {
            $valor = $row['valor'];
            if (in_array($row['chave'], $this->sensitiveKeys)) {
                $valor = \App\Utils\Security::decrypt($valor);
            }
            $config[$row['chave']] = $valor;
        }
        return $config;
    }

    public function get($chave, $default = null)
    {
        $stmt = $this->db->prepare("SELECT valor FROM {$this->table} WHERE chave = ? LIMIT 1");
        $stmt->execute([$chave]);
        $res = $stmt->fetch();
        
        if ($res) {
            $valor = $res['valor'];
            if (in_array($chave, $this->sensitiveKeys)) {
                $valor = \App\Utils\Security::decrypt($valor);
            }
            return $valor;
        }
        return $default;
    }

    public function updateMany($data)
    {
        foreach ($data as $chave => $valor) {
            // Process sensitive keys
            if (in_array($chave, $this->sensitiveKeys)) {
                if ($valor === '********') {
                    continue; // Skip updating this specific key
                }
                $valor = \App\Utils\Security::encrypt($valor);
            }
            
            $stmt = $this->db->prepare("UPDATE {$this->table} SET valor = ? WHERE chave = ?");
            $stmt->execute([$valor, $chave]);
        }
        return true;
    }
}
