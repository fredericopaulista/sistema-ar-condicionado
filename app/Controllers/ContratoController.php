<?php

namespace App\Controllers;

use App\Models\Orcamento;
use App\Middlewares\AuthMiddleware;

class ContratoController extends BaseController
{
    private $orcamentoModel;

    public function __construct()
    {
        AuthMiddleware::permission('contratos.manage');
        $this->orcamentoModel = new Orcamento();
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
        // Simple refresh not needed for local signatures
        $this->redirect('/contratos');
    }

    public function reenviar($id)
    {
        $stmt = $this->orcamentoModel->getConnection()->prepare("
            SELECT o.*, c.nome as cliente_nome, c.email as cliente_email
            FROM orcamentos o
            JOIN clientes c ON o.cliente_id = c.id
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        $orcamento = $stmt->fetch();

        if ($orcamento) {
            $emailService = new \App\Services\EmailService();
            $config = require __DIR__ . '/../../config/config.php';
            $link = $config['app_url'] . '/p/' . $orcamento['token_publico'];

            if ($emailService->sendContract($orcamento['cliente_email'], $orcamento['cliente_nome'], $orcamento['numero'], $link)) {
                $logModel = new \App\Models\Log();
                $logModel->record($id, 'Link de assinatura reenviado para o cliente');
            }
        }
        $this->redirect('/contratos');
    }

    public function enviarCopia($id)
    {
        $stmt = $this->orcamentoModel->getConnection()->prepare("
            SELECT o.*, c.nome as cliente_nome, c.email as cliente_email
            FROM orcamentos o
            JOIN clientes c ON o.cliente_id = c.id
            WHERE o.id = ? AND o.status = 'assinado'
        ");
        $stmt->execute([$id]);
        $orcamento = $stmt->fetch();

        if ($orcamento && !empty($orcamento['contrato_pdf'])) {
            $pdfPath = dirname(__DIR__, 2) . '/storage/contratos/' . $orcamento['contrato_pdf'];
            
            $emailService = new \App\Services\EmailService();
            if ($emailService->sendSignedContractNotification($orcamento['cliente_email'], $orcamento['cliente_nome'], $orcamento['numero'], $pdfPath)) {
                $logModel = new \App\Models\Log();
                $logModel->record($id, 'Cópia do contrato assinado enviada para o cliente');
            }
        }
        $this->redirect('/contratos');
    }

    public function download($id)
    {
        $orcamento = $this->orcamentoModel->find($id);
        if ($orcamento && !empty($orcamento['contrato_pdf'])) {
            $pdfPath = dirname(__DIR__, 2) . '/storage/contratos/' . $orcamento['contrato_pdf'];
            
            if (file_exists($pdfPath)) {
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . $orcamento['contrato_pdf'] . '"');
                header('Content-Length: ' . filesize($pdfPath));
                readfile($pdfPath);
                exit;
            }
        }
        $this->redirect('/contratos');
    }

    public function deletar($id)
    {
        // For contracts, "delete" just means stopping the signature flow
        // We revert status to 'aprovado' so it doesn't show in the contracts list
        $this->orcamentoModel->updateStatus($id, 'aprovado');
        
        $logModel = new \App\Models\Log();
        $logModel->record($id, 'Contrato removido da lista de assinatura');
        
        $this->redirect('/contratos');
    }
}
