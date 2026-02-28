<?php

namespace App\Controllers;

use App\Models\Orcamento;
use App\Models\Customer;

class PortalController extends BaseController
{
    private $orcamentoModel;

    public function __construct()
    {
        $this->orcamentoModel = new Orcamento();
    }

    public function viewByToken($token)
    {
        $stmt = $this->orcamentoModel->getConnection()->prepare("
            SELECT o.*, c.nome as cliente_nome, c.email as cliente_email, c.telefone as cliente_telefone, 
                   c.endereco as cliente_endereco, c.numero as cliente_numero, c.complemento as cliente_complemento, c.bairro as cliente_bairro, c.cep as cliente_cep
            FROM orcamentos o
            JOIN clientes c ON o.cliente_id = c.id
            WHERE o.token_publico = ?
        ");
        $stmt->execute([$token]);
        $orcamento = $stmt->fetch();

        if (!$orcamento) {
            http_response_code(404);
            die("Orçamento não encontrado.");
        }

        // Get items separately
        $stmt = $this->orcamentoModel->getConnection()->prepare("SELECT * FROM itens_orcamento WHERE orcamento_id = ?");
        $stmt->execute([$orcamento['id']]);
        $orcamento['itens'] = $stmt->fetchAll();

        // Update status to 'visualizado' if it was 'enviado' or 'criado'
        if (in_array($orcamento['status'], ['criado', 'enviado'])) {
            $this->orcamentoModel->updateStatus($orcamento['id'], 'visualizado');
        }

        // Log View
        $logModel = new \App\Models\Log();
        $logModel->record($orcamento['id'], 'Visualizado via portal');

        $this->view('client/view', [
            'orcamento' => $orcamento
        ]);
    }

    public function approve($token)
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $signatureBase64 = $data['signature'] ?? '';

        if (empty($signatureBase64)) {
            $this->json(['success' => false, 'message' => 'A assinatura é obrigatória.'], 400);
            return;
        }

        try {
            file_put_contents('/Users/fredmoura/Downloads/sistema-ar/storage/logs/debug.log', date('[Y-m-d H:i:s] ') . "Starting approval for token: $token\n", FILE_APPEND);
            
            $stmt = $this->orcamentoModel->getConnection()->prepare("
                SELECT o.*, c.nome as cliente_nome, c.email as cliente_email, c.cpf_cnpj as cliente_cpf_cnpj, 
                       c.endereco as cliente_endereco, c.numero as cliente_numero, c.complemento as cliente_complemento, c.bairro as cliente_bairro, c.cep as cliente_cep
                FROM orcamentos o
                JOIN clientes c ON o.cliente_id = c.id
                WHERE o.token_publico = ?
            ");
            $stmt->execute([$token]);
            $orcamento = $stmt->fetch();

            if ($orcamento) {
                file_put_contents('/Users/fredmoura/Downloads/sistema-ar/storage/logs/debug.log', date('[Y-m-d H:i:s] ') . "Quote found: " . $orcamento['id'] . "\n", FILE_APPEND);
                
                $logModel = new \App\Models\Log();
                $logModel->record($orcamento['id'], 'Aprovação e assinatura pelo portal');

                // 1. Process Signature
                $sigService = new \App\Services\SignatureService();
                $imagePath = $sigService->saveSignature($orcamento['id'], $signatureBase64);
                
                file_put_contents('/Users/fredmoura/Downloads/sistema-ar/storage/logs/debug.log', date('[Y-m-d H:i:s] ') . "Signature saved: $imagePath\n", FILE_APPEND);
                
                $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
                $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
                $hash = $sigService->generateSignatureHash($orcamento, $ip, $ua);

                // 2. Update Status and Signature Data
                $stmt = $this->orcamentoModel->getConnection()->prepare("
                    UPDATE orcamentos SET 
                    status = 'assinado', 
                    assinatura_imagem = ?, 
                    assinatura_ip = ?, 
                    assinatura_data = NOW(), 
                    assinatura_hash = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$imagePath, $ip, $hash, $orcamento['id']]);
                file_put_contents('/Users/fredmoura/Downloads/sistema-ar/storage/logs/debug.log', date('[Y-m-d H:i:s] ') . "Database updated\n", FILE_APPEND);

                // 3. Record Financial Entry (Entrada)
                $financeiroModel = new \App\Models\Financeiro();
                $financeiroModel->create([
                    'descricao' => 'Pagamento Orçamento ' . $orcamento['numero'] . ' - ' . $orcamento['cliente_nome'],
                    'valor' => $orcamento['valor_final'],
                    'tipo' => 'entrada',
                    'categoria' => 'Serviços',
                    'data_transacao' => date('Y-m-d'),
                    'orcamento_id' => $orcamento['id']
                ]);
                file_put_contents('/Users/fredmoura/Downloads/sistema-ar/storage/logs/debug.log', date('[Y-m-d H:i:s] ') . "Finance record created\n", FILE_APPEND);

                // 4. Generate PDF with signature info
                $orcamento['assinatura_imagem'] = $imagePath;
                $orcamento['assinatura_ip'] = $ip;
                $orcamento['assinatura_data'] = date('Y-m-d H:i:s');
                $orcamento['assinatura_hash'] = $hash;
                
                $stmtItems = $this->orcamentoModel->getConnection()->prepare("SELECT * FROM itens_orcamento WHERE orcamento_id = ?");
                $stmtItems->execute([$orcamento['id']]);
                $orcamento['itens'] = $stmtItems->fetchAll();
                
                file_put_contents('/Users/fredmoura/Downloads/sistema-ar/storage/logs/debug.log', date('[Y-m-d H:i:s] ') . "Starting PDF generation\n", FILE_APPEND);
                \App\Services\ContratoService::gerarPDF($orcamento);
                file_put_contents('/Users/fredmoura/Downloads/sistema-ar/storage/logs/debug.log', date('[Y-m-d H:i:s] ') . "PDF generated\n", FILE_APPEND);

                $this->json(['success' => true, 'message' => 'Orçamento aprovado e assinado com sucesso!']);
            } else {
                file_put_contents('/Users/fredmoura/Downloads/sistema-ar/storage/logs/debug.log', date('[Y-m-d H:i:s] ') . "Quote NOT found for token: $token\n", FILE_APPEND);
                $this->json(['success' => false, 'message' => 'Orçamento não encontrado.'], 404);
            }
        } catch (\Exception $e) {
            file_put_contents('/Users/fredmoura/Downloads/sistema-ar/storage/logs/debug.log', date('[Y-m-d H:i:s] ') . "ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
            $this->json(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()], 500);
        }
    }

    public function requestChange($token)
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $mensagem = $data['mensagem'] ?? '';

        $stmt = $this->orcamentoModel->getConnection()->prepare("SELECT id FROM orcamentos WHERE token_publico = ?");
        $stmt->execute([$token]);
        $orc = $stmt->fetch();

        if ($orc) {
            $logModel = new \App\Models\Log();
            $logModel->record($orc['id'], 'Alteração solicitada: ' . $mensagem);
            
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Orçamento não encontrado.'], 404);
        }
    }
}
