<?php

namespace App\Controllers;

use App\Models\Financeiro;
use App\Middlewares\AuthMiddleware;

class FinanceiroController extends BaseController
{
    private $financeiroModel;

    public function __construct()
    {
        AuthMiddleware::check();
        $this->financeiroModel = new Financeiro();
    }

    public function index()
    {
        $transacoes = $this->financeiroModel->all();
        
        // Stats for cards
        $db = \App\Services\Database::getInstance();
        $totalEntradas = $db->query("SELECT SUM(valor) FROM financeiro WHERE tipo = 'entrada'")->fetchColumn() ?: 0;
        $totalSaidas = $db->query("SELECT SUM(valor) FROM financeiro WHERE tipo = 'saida'")->fetchColumn() ?: 0;
        $saldo = $totalEntradas - $totalSaidas;

        $this->view('admin/financeiro/index', [
            'title' => 'Financeiro',
            'transacoes' => $transacoes,
            'totalEntradas' => $totalEntradas,
            'totalSaidas' => $totalSaidas,
            'saldo' => $saldo
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'descricao' => $_POST['descricao'],
                'valor' => str_replace(',', '.', str_replace('.', '', $_POST['valor'])),
                'tipo' => $_POST['tipo'],
                'categoria' => $_POST['categoria'],
                'data_transacao' => $_POST['data_transacao'],
                'orcamento_id' => !empty($_POST['orcamento_id']) ? $_POST['orcamento_id'] : null
            ];

            if ($this->financeiroModel->create($data)) {
                header('Location: /financeiro?success=1');
                exit;
            }
        }
    }

    public function delete($id)
    {
        if ($this->financeiroModel->delete($id)) {
            header('Location: /financeiro?deleted=1');
            exit;
        }
    }
}
