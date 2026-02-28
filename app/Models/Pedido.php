<?php

namespace App\Models;

use PDO;

class Pedido extends BaseModel
{
    protected $table = 'pedidos';

    public function allWithDetails()
    {
        $stmt = $this->db->query("
            SELECT p.*, o.numero as orcamento_numero, c.nome as cliente_nome, o.valor_final
            FROM {$this->table} p
            JOIN orcamentos o ON p.orcamento_id = o.id
            JOIN clientes c ON o.cliente_id = c.id
            ORDER BY p.data_pedido DESC
        ");
        return $stmt->fetchAll();
    }

    public function findWithDetails($id)
    {
        $stmt = $this->db->prepare("
            SELECT p.*, o.numero as orcamento_numero, o.data_emissao, o.valor_final, o.forma_pagamento, o.observacoes as orcamento_obs,
                   c.nome as cliente_nome, c.email as cliente_email, c.telefone as cliente_telefone, 
                   c.endereco as cliente_endereco, c.numero as cliente_numero, c.bairro as cliente_bairro
            FROM {$this->table} p
            JOIN orcamentos o ON p.orcamento_id = o.id
            JOIN clientes c ON o.cliente_id = c.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createFromOrcamento($orcamentoId)
    {
        // Check if order already exists to avoid duplicates
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE orcamento_id = ?");
        $stmt->execute([$orcamentoId]);
        if ($stmt->fetch()) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO {$this->table} (orcamento_id, status) VALUES (?, 'pendente')");
        return $stmt->execute([$orcamentoId]);
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function updateNotes($id, $notes)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET observacoes_tecnicas = ? WHERE id = ?");
        return $stmt->execute([$notes, $id]);
    }
}
