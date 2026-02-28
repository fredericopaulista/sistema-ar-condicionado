<?php

namespace App\Models;

class Log extends BaseModel
{
    protected $table = 'logs_orcamento';

    public function record($orcamentoId, $acao)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (orcamento_id, acao, ip, user_agent) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $orcamentoId,
            $acao,
            $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ]);
    }

    public function getByOrcamento($orcamentoId)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE orcamento_id = ? ORDER BY created_at DESC");
        $stmt->execute([$orcamentoId]);
        return $stmt->fetchAll();
    }
}
