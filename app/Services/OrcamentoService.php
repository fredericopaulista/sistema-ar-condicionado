<?php

namespace App\Services;

class OrcamentoService
{
    public static function gerarNumero()
    {
        $db = Database::getInstance();
        $yearMonth = date('Ym');
        $pattern = 'ORC-' . $yearMonth . '%';
        
        $stmt = $db->prepare("SELECT numero FROM orcamentos WHERE numero LIKE ? ORDER BY numero DESC LIMIT 1");
        $stmt->execute([$pattern]);
        $res = $stmt->fetch();
        
        if ($res) {
            $lastNumero = $res['numero'];
            $lastSequence = (int)substr($lastNumero, -4);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }
        
        return 'ORC-' . $yearMonth . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
    }

    public static function gerarToken()
    {
        return bin2hex(random_bytes(32));
    }

    public static function calcularTotais($itens, $desconto = 0)
    {
        $subtotal = 0;
        foreach ($itens as $item) {
            $subtotal += $item['quantidade'] * $item['valor_unitario'];
        }
        
        return [
            'valor_total' => $subtotal,
            'valor_final' => max(0, $subtotal - $desconto)
        ];
    }
}
