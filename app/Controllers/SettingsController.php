<?php

namespace App\Controllers;

use App\Models\Template;
use App\Middlewares\AuthMiddleware;

class SettingsController extends BaseController
{
    private $templateModel;
    private $configModel;

    public function __construct()
    {
        AuthMiddleware::check();
        $this->templateModel = new \App\Models\Template();
        $this->configModel = new \App\Models\Configuracao();
    }

    public function index()
    {
        $template = $this->templateModel->findByName('contrato_padrao');
        $config = $this->configModel->all();

        $this->view('admin/settings/contract', [
            'title' => 'Configurações do Sistema',
            'template' => $template,
            'config' => $config
        ]);
    }

    public function update()
    {
        $content = $_POST['conteudo'] ?? '';
        $sysConfig = $_POST['config'] ?? [];
        
        $success = true;
        
        if (!empty($content)) {
            $success = $success && $this->templateModel->updateContent('contrato_padrao', $content);
        }

        if (!empty($sysConfig)) {
            $success = $success && $this->configModel->updateMany($sysConfig);
        }

        if ($success) {
            $this->redirect('/configuracoes?success=1');
        } else {
            $this->redirect('/configuracoes?error=1');
        }
    }

    public function testEmail()
    {
        $emailService = new \App\Services\EmailService();
        $config = $this->configModel->all();
        $to = $config['mail_user'] ?? '';
        
        if (!empty($to) && $emailService->sendQuote($to, 'Teste de Configuração', 'TEST-001', '#')) {
            $this->redirect('/configuracoes?test_success=1');
        } else {
            $this->redirect('/configuracoes?test_error=1');
        }
    }
}
