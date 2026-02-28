<?php

namespace App\Controllers;

class BaseController
{
    protected function view($path, $data = [])
    {
        // Inject Global Company Config
        $configModel = new \App\Models\Configuracao();
        $data['global_company'] = $configModel->all();

        extract($data);
        require_once __DIR__ . "/../../views/{$path}.php";
    }

    protected function json($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }
}
