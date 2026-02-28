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
            // Log Approval attempt
            $logModel = new \App\Models\Log();
            $logModel->record($orcamento['id'], 'Aprovação iniciada');
            // Get items
            $stmt = $this->orcamentoModel->getConnection()->prepare("SELECT * FROM itens_orcamento WHERE orcamento_id = ?");
            $stmt->execute([$orcamento['id']]);
            $orcamento['itens'] = $stmt->fetchAll();

            // 1. Generate PDF
            $pdfResult = \App\Services\ContratoService::gerarPDF($orcamento);

            // 2. Send to Assinafy
            $assinafy = new \App\Services\AssinafyService();
            $result = $assinafy->enviarParaAssinatura($orcamento, $pdfResult['base64']);

            if ($result['success']) {
                // 3. Update Status
                $stmt = $this->orcamentoModel->getConnection()->prepare("
                    UPDATE orcamentos SET 
                    status = 'contrato_enviado', 
                    assinafy_doc_id = ?, 
                    link_assinatura = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$result['document_id'], $result['sign_url'], $orcamento['id']]);

                // Send Email via SMTP
                $emailService = new \App\Services\EmailService();
                $emailService->sendContract($orcamento['cliente_email'], $orcamento['cliente_nome'], $orcamento['numero'], $result['sign_url']);

                $this->json(['success' => true, 'message' => 'Orçamento aprovado e contrato enviado!']);
            } else {
                $this->json(['success' => false, 'message' => 'Orçamento aprovado internamente, mas houve erro ao enviar para assinatura digital: ' . $result['message']]);
            }
        } else {
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
