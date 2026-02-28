<?php

namespace App\Controllers;

use App\Models\Template;
use App\Middlewares\AuthMiddleware;

class SettingsController extends BaseController
{
    private $templateModel;

    public function __construct()
    {
        AuthMiddleware::check();
        $this->templateModel = new Template();
    }

    public function index()
    {
        $template = $this->templateModel->findByName('contrato_padrao');
        $this->view('admin/settings/contract', [
            'title' => 'Configurações de Contrato',
            'template' => $template
        ]);
    }

    public function update()
    {
        $content = $_POST['conteudo'] ?? '';
        
        if ($this->templateModel->updateContent('contrato_padrao', $content)) {
            $this->redirect('/configuracoes?success=1');
        } else {
            $this->redirect('/configuracoes?error=1');
        }
    }

    public function testEmail()
    {
        $emailService = new \App\Services\EmailService();
        $config = require __DIR__ . '/../../config/config.php';
        $to = $config['mail']['user']; // Send to themselves
        
        if ($emailService->sendQuote($to, 'Teste de Configuração', 'TEST-001', $config['app_url'])) {
            $this->redirect('/configuracoes?test_success=1');
        } else {
            $this->redirect('/configuracoes?test_error=1');
        }
    }
}
