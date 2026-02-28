<?php

namespace App\Models;

class Template extends BaseModel
{
    protected $table = 'templates';

    public function findByName($name)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE nome = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    public function updateContent($name, $content)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET conteudo = ? WHERE nome = ?");
        return $stmt->execute([$content, $name]);
    }
}
