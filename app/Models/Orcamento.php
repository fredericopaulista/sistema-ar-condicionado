<?php

namespace App\Models;

use PDO;

class Orcamento extends BaseModel
{
    protected $table = 'orcamentos';

    public function allWithClient()
    {
        $stmt = $this->db->query("
            SELECT o.*, c.nome as cliente_nome 
            FROM {$this->table} o 
            JOIN clientes c ON o.cliente_id = c.id 
            ORDER BY o.id DESC
        ");
        return $stmt->fetchAll();
    }

    public function findWithItems($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $orcamento = $stmt->fetch();

        if ($orcamento) {
            $stmt = $this->db->prepare("SELECT * FROM itens_orcamento WHERE orcamento_id = ?");
            $stmt->execute([$id]);
            $orcamento['itens'] = $stmt->fetchAll();
        }

        return $orcamento;
    }

    public function create($data, $itens)
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO {$this->table} 
                (cliente_id, numero, data_emissao, validade_dias, valor_total, desconto, valor_final, forma_pagamento, observacoes, status, token_publico) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $data['cliente_id'],
                $data['numero'],
                $data['data_emissao'],
                $data['validade_dias'],
                $data['valor_total'],
                $data['desconto'],
                $data['valor_final'],
                $data['forma_pagamento'],
                $data['observacoes'],
                'criado',
                $data['token_publico']
            ]);

            $orcamentoId = $this->db->lastInsertId();

            $stmtItem = $this->db->prepare("
                INSERT INTO itens_orcamento (orcamento_id, categoria, descricao, quantidade, valor_unitario, valor_total) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            foreach ($itens as $item) {
                $stmtItem->execute([
                    $orcamentoId,
                    $item['categoria'],
                    $item['descricao'],
                    $item['quantidade'],
                    $item['valor_unitario'],
                    $item['valor_total']
                ]);
            }

            $this->db->commit();
            return $orcamentoId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
