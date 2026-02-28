<?php

namespace App\Controllers;

use App\Models\Pedido;
use App\Models\Orcamento;
use App\Middlewares\AuthMiddleware;

class PedidoController extends BaseController
{
    private $pedidoModel;

    public function __construct()
    {
        AuthMiddleware::permission('financeiro.manage'); // Reusing financial permission for now
        die('Constructor reached');
        $this->pedidoModel = new Pedido();
    }

    public function index()
    {
        die('Index method reached');
        $pedidos = $this->pedidoModel->allWithDetails();
        $this->view('admin/orders/index', [
            'title' => 'Gestão de Pedidos (Execução)',
            'pedidos' => $pedidos
        ]);
    }

    public function view($id)
    {
        $pedido = $this->pedidoModel->findWithDetails($id);
        if (!$pedido) {
            $this->redirect('/pedidos');
        }

        // Get original items
        $orcamentoModel = new Orcamento();
        $orcamento = $orcamentoModel->findWithItems($pedido['orcamento_id']);

        $this->view('admin/orders/view', [
            'title' => 'Pedido #' . $pedido['id'],
            'pedido' => $pedido,
            'orcamento' => $orcamento
        ]);
    }

    public function update($id)
    {
        $status = $_POST['status'] ?? null;
        $notes = $_POST['observacoes_tecnicas'] ?? null;

        if ($status) {
            $this->pedidoModel->updateStatus($id, $status);
        }
        if ($notes !== null) {
            $this->pedidoModel->updateNotes($id, $notes);
        }

        $this->redirect('/pedidos/view/' . $id . '?success=1');
    }
}
