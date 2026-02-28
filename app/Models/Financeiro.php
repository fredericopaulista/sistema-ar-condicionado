<?php

namespace App\Models;

use PDO;

class Financeiro extends BaseModel
{
    protected $table = 'financeiro';

    public function all($limit = 100)
    {
        $stmt = $this->db->prepare("
            SELECT f.*, o.numero as orcamento_numero 
            FROM {$this->table} f 
            LEFT JOIN orcamentos o ON f.orcamento_id = o.id 
            ORDER BY f.data_transacao DESC, f.id DESC 
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (descricao, valor, tipo, categoria, data_transacao, orcamento_id) 
                VALUES (:descricao, :valor, :tipo, :categoria, :data_transacao, :orcamento_id)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':descricao' => $data['descricao'],
            ':valor' => $data['valor'],
            ':tipo' => $data['tipo'],
            ':categoria' => $data['categoria'] ?? null,
            ':data_transacao' => $data['data_transacao'],
            ':orcamento_id' => $data['orcamento_id'] ?? null
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getMonthlyStats($months = 6)
    {
        $sql = "
            SELECT 
                DATE_FORMAT(data_transacao, '%Y-%m') as mes,
                SUM(CASE WHEN tipo = 'entrada' THEN valor ELSE 0 END) as entradas,
                SUM(CASE WHEN tipo = 'saida' THEN valor ELSE 0 END) as saidas
            FROM {$this->table}
            WHERE data_transacao >= DATE_SUB(CURRENT_DATE, INTERVAL :months MONTH)
            GROUP BY mes
            ORDER BY mes ASC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':months', (int)$months, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
