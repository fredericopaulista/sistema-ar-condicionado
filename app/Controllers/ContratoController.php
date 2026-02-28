<?php

namespace App\Controllers;

use App\Models\Orcamento;
use App\Services\AssinafyService;
use App\Middlewares\AuthMiddleware;

class ContratoController extends BaseController
{
    private $orcamentoModel;
    private $assinafyService;

    public function __construct()
    {
        AuthMiddleware::permission('contratos.manage');
        $this->orcamentoModel = new Orcamento();
        $this->assinafyService = new AssinafyService();
    }

    public function index()
    {
        // Fetch all quotes that have been sent for signature or are signed
        $stmt = $this->orcamentoModel->getConnection()->query("
            SELECT o.*, c.nome as cliente_nome, c.email as cliente_email
            FROM orcamentos o 
            JOIN clientes c ON o.cliente_id = c.id 
            WHERE o.status IN ('contrato_enviado', 'assinado')
            ORDER BY o.created_at DESC
        ");
        $contratos = $stmt->fetchAll();

        $this->view('admin/contracts/index', [
            'title' => 'Contratos',
            'contratos' => $contratos
        ]);
    }

    public function refresh($id)
    {
        $orcamento = $this->orcamentoModel->findWithItems($id);
        
        if (!$orcamento || empty($orcamento['assinafy_doc_id'])) {
            $this->redirect('/contratos');
        }

        $result = $this->assinafyService->getDocumentStatus($orcamento['assinafy_doc_id']);

        if ($result['success'] && $result['status'] === 'signed') {
            $this->orcamentoModel->updateStatus($id, 'assinado');
        }

        $this->redirect('/contratos');
    }

    public function resend($id)
    {
        $stmt = $this->orcamentoModel->getConnection()->prepare("
            SELECT o.*, c.nome as cliente_nome, c.email as cliente_email, c.cpf_cnpj as cliente_cpf_cnpj, 
                   c.endereco as cliente_endereco, c.numero as cliente_numero, c.bairro as cliente_bairro, c.cep as cliente_cep
            FROM orcamentos o
            JOIN clientes c ON o.cliente_id = c.id
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        $orcamento = $stmt->fetch();

        if ($orcamento) {
            // Get items
            $stmt = $this->orcamentoModel->getConnection()->prepare("SELECT * FROM itens_orcamento WHERE orcamento_id = ?");
            $stmt->execute([$id]);
            $orcamento['itens'] = $stmt->fetchAll();

            // 1. Generate PDF again
            $pdfResult = \App\Services\ContratoService::gerarPDF($orcamento);

            // 2. Send to Assinafy again (creates a new document/link)
            $result = $this->assinafyService->enviarParaAssinatura($orcamento, $pdfResult['base64']);

            if ($result['success']) {
                $stmt = $this->orcamentoModel->getConnection()->prepare("
                    UPDATE orcamentos SET 
                    status = 'contrato_enviado', 
                    assinafy_doc_id = ?, 
                    link_assinatura = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$result['document_id'], $result['sign_url'], $id]);

                // Send Email via SMTP
                $emailService = new \App\Services\EmailService();
                $emailService->sendContract($orcamento['cliente_email'], $orcamento['cliente_nome'], $orcamento['numero'], $result['sign_url']);
            }
        }

        $this->redirect('/contratos');
    }
}
