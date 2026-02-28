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
        AuthMiddleware::check();
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
            ORDER BY o.updated_at DESC
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
}
