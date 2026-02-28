<?php

namespace App\Controllers;

use App\Models\Orcamento;
use App\Models\Customer;
use App\Services\OrcamentoService;
use App\Middlewares\AuthMiddleware;

class OrcamentoController extends BaseController
{
    private $orcamentoModel;
    private $customerModel;

    public function __construct()
    {
        AuthMiddleware::check();
        $this->orcamentoModel = new Orcamento();
        $this->customerModel = new Customer();
    }

    public function index()
    {
        $orcamentos = $this->orcamentoModel->allWithClient();
        $this->view('admin/quotes/index', [
            'title' => 'Orçamentos',
            'orcamentos' => $orcamentos
        ]);
    }

    public function create()
    {
        $clientes = $this->customerModel->all();
        $proximoNumero = OrcamentoService::gerarNumero();
        
        $this->view('admin/quotes/create', [
            'title' => 'Novo Orçamento',
            'clientes' => $clientes,
            'proximoNumero' => $proximoNumero
        ]);
    }

    public function store()
    {
        $data = $_POST;
        $itensRaw = $_POST['itens'] ?? [];
        $itens = [];

        // Process items
        foreach ($itensRaw['descricao'] as $i => $desc) {
            if (empty($desc)) continue;
            
            $qtd = (int)$itensRaw['quantidade'][$i];
            $valorUnit = (float)$itensRaw['valor_unitario'][$i];
            
            $itens[] = [
                'categoria' => $itensRaw['categoria'][$i],
                'descricao' => $desc,
                'quantidade' => $qtd,
                'valor_unitario' => $valorUnit,
                'valor_total' => $qtd * $valorUnit
            ];
        }

        // Calculate totals
        $totais = OrcamentoService::calcularTotais($itens, (float)($data['desconto'] ?? 0));
        
        $data['valor_total'] = $totais['valor_total'];
        $data['valor_final'] = $totais['valor_final'];
        $data['token_publico'] = OrcamentoService::gerarToken();
        $data['status'] = 'criado';

        $id = $this->orcamentoModel->create($data, $itens);

        // Send Email
        if ($id) {
            $logModel = new \App\Models\Log();
            $logModel->record($id, 'Orçamento criado');

            $cliente = $this->customerModel->find($data['cliente_id']);
            $emailService = new \App\Services\EmailService();
            $config = require __DIR__ . '/../../config/config.php';
            $link = $config['app_url'] . '/p/' . $data['token_publico'];
            
            if ($emailService->sendQuote($cliente['email'], $cliente['nome'], $data['numero'], $link)) {
                $this->orcamentoModel->updateStatus($id, 'enviado');
                $logModel->record($id, 'E-mail enviado para cliente');
            }
        }

        $this->redirect('/orcamentos');
    }

    public function delete($id)
    {
        $this->orcamentoModel->delete($id);
        $this->redirect('/orcamentos');
    }
}
