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
        AuthMiddleware::permission('configuracoes.manage');
        $this->templateModel = new \App\Models\Template();
        $this->configModel = new \App\Models\Configuracao();
    }

    public function index()
    {
        $template = $this->templateModel->findByName('contrato_padrao');
        $config = $this->configModel->all();

        // Redact sensitive data for the view
        foreach (['mail_pass'] as $key) {
            if (!empty($config[$key])) {
                $config[$key] = \App\Utils\Security::redact($config[$key]);
            }
        }

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
            // Handle Logo Upload
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__, 2) . '/public/assets/img/logo/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                }
                
                $extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                $filename = 'logo_' . time() . '.' . $extension;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
                    $sysConfig['company_logo'] = 'assets/img/logo/' . $filename;
                }
            }

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
