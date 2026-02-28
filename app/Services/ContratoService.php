<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class ContratoService
{
    public static function gerarPDF($orcamento)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        
        $html = self::renderTemplate($orcamento);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $filename = 'contrato_' . $orcamento['numero'] . '_' . time() . '.pdf';
        $path = __DIR__ . '/../../storage/contratos/' . $filename;
        
        file_put_contents($path, $output);
        
        return [
            'path' => $path,
            'filename' => $filename,
            'base64' => base64_encode($output)
        ];
    }

    private static function renderTemplate($orcamento)
    {
        $templateModel = new \App\Models\Template();
        $template = $templateModel->findByName('contrato_padrao');
        
        $html = $template['conteudo'];

        $description = "";
        foreach ($orcamento['itens'] as $item) {
            $description .= "• {$item['categoria']}: {$item['descricao']} (Qtd: {$item['quantidade']})<br>";
        }

        $fullAddress = $orcamento['cliente_endereco'];
        if (!empty($orcamento['cliente_numero'])) $fullAddress .= ", " . $orcamento['cliente_numero'];
        if (!empty($orcamento['cliente_bairro'])) $fullAddress .= " - " . $orcamento['cliente_bairro'];
        if (!empty($orcamento['cliente_cep'])) $fullAddress .= " | CEP: " . $orcamento['cliente_cep'];

        $vars = [
            '{{cliente_nome}}' => $orcamento['cliente_nome'],
            '{{cliente_cpf_cnpj}}' => $orcamento['cliente_cpf_cnpj'],
            '{{cliente_endereco}}' => $fullAddress,
            '{{descricao_servicos}}' => $description,
            '{{valor_total}}' => number_format($orcamento['valor_final'], 2, ',', '.'),
            '{{forma_pagamento}}' => $orcamento['forma_pagamento'],
            '{{data_atual}}' => date('d/m/Y')
        ];

        return str_replace(array_keys($vars), array_values($vars), $html);
    }
}
