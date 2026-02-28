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

        // Blocks for signature
        $signatureHtml = "";
        if (!empty($orcamento['assinatura_imagem'])) {
            $basePath = '/Users/fredmoura/Downloads/sistema-ar/';
            $imagePath = $basePath . $orcamento['assinatura_imagem'];
            if (file_exists($imagePath)) {
                $type = pathinfo($imagePath, PATHINFO_EXTENSION);
                $imageData = base64_encode(file_get_contents($imagePath));
                $signatureHtml = "
                    <div style='margin-top: 50px; border-top: 1px solid #e2e8f0; padding-top: 20px;'>
                        <div style='text-align: center;'>
                            <img src='data:image/{$type};base64,{$imageData}' style='max-height: 80px; margin-bottom: 5px;'><br>
                            <strong style='font-size: 14px; text-transform: uppercase;'>{$orcamento['cliente_nome']}</strong><br>
                            <span style='font-size: 10px; color: #64748b;'>Assinado eletronicamente via Portal do Cliente</span>
                        </div>
                        <div style='margin-top: 20px; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 9px; color: #64748b; line-height: 1.4;'>
                            <strong>LOG DE AUDITORIA DE ASSINATURA:</strong><br>
                            ID do Orçamento: {$orcamento['numero']}<br>
                            Data/Hora: " . date('d/m/Y H:i:s', strtotime($orcamento['assinatura_data'])) . "<br>
                            Endereço IP: {$orcamento['assinatura_ip']}<br>
                            Hash de Integridade: {$orcamento['assinatura_hash']}
                        </div>
                    </div>
                ";
            }
        }

        $vars = [
            '{{cliente_nome}}' => $orcamento['cliente_nome'],
            '{{cliente_cpf_cnpj}}' => $orcamento['cliente_cpf_cnpj'],
            '{{cliente_endereco}}' => $fullAddress,
            '{{descricao_servicos}}' => $description,
            '{{valor_total}}' => number_format($orcamento['valor_final'], 2, ',', '.'),
            '{{forma_pagamento}}' => $orcamento['forma_pagamento'],
            '{{data_atual}}' => date('d/m/Y'),
            '{{assinatura_digital}}' => $signatureHtml
        ];

        $rendered = str_replace(array_keys($vars), array_values($vars), $html);
        
        // Auto-append if tag missing
        if (strpos($html, '{{assinatura_digital}}') === false) {
            $rendered .= "<!-- Auto-generated Signature Block -->" . $signatureHtml;
        }

        return $rendered;
    }
}
