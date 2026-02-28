<?php

namespace App\Controllers;

use App\Models\Customer;
use App\Models\Orcamento; // Will create this next
use App\Middlewares\AuthMiddleware;

class DashboardController extends BaseController
{
    public function __construct()
    {
        AuthMiddleware::check();
    }

    public function index()
    {
        $db = \App\Services\Database::getInstance();
        
        // Total Clientes
        $totalClientes = $db->query("SELECT COUNT(id) FROM clientes")->fetchColumn();
        
        // Total Orçamentos
        $totalOrcamentos = $db->query("SELECT COUNT(id) FROM orcamentos")->fetchColumn();
        
        // Receita Assinada
        $receita = $db->query("SELECT SUM(valor_final) FROM orcamentos WHERE status = 'assinado'")->fetchColumn() ?: 0;

        // Receita Potencial (todos menos cancelados e assinados)
        $receitaPotencial = $db->query("SELECT SUM(valor_final) FROM orcamentos WHERE status NOT IN ('cancelado', 'assinado')")->fetchColumn() ?: 0;

        // Taxas
        $aprovados = $db->query("SELECT COUNT(id) FROM orcamentos WHERE status IN ('aprovado', 'contrato_enviado', 'assinado')")->fetchColumn();
        $assinados = $db->query("SELECT COUNT(id) FROM orcamentos WHERE status = 'assinado'")->fetchColumn();
        
        $taxaAprovacao = $totalOrcamentos > 0 ? ($aprovados / $totalOrcamentos) * 100 : 0;
        $taxaAssinatura = $totalOrcamentos > 0 ? ($assinados / $totalOrcamentos) * 100 : 0;

        // Atividades Recentes
        $atividades = $db->query("
            SELECT o.numero, c.nome as cliente, o.status, o.created_at 
            FROM orcamentos o 
            JOIN clientes c ON o.cliente_id = c.id 
            ORDER BY o.created_at DESC 
            LIMIT 5
        ")->fetchAll();

        $this->view('admin/dashboard', [
            'title' => 'Dashboard',
            'totalClientes' => $totalClientes,
            'totalOrcamentos' => $totalOrcamentos,
            'receita' => $receita,
            'receitaPotencial' => $receitaPotencial,
            'taxaAprovacao' => $taxaAprovacao,
            'taxaAssinatura' => $taxaAssinatura,
            'atividades' => $atividades
        ]);
    }
}
