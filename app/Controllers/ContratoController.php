<?php

namespace App\Controllers;

use App\Models\Orcamento;
use App\Middlewares\AuthMiddleware;

class ContratoController extends BaseController
{
    private $orcamentoModel;
    private $assinafyService;

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

    public function resend($id)
    {
        // Resending logic for local signatures: Just redirect to portal link
        $orcamento = $this->orcamentoModel->find($id);
        if ($orcamento) {
            $this->redirect('/p/' . $orcamento['token_publico']);
        }
        $this->redirect('/contratos');
    }
}
