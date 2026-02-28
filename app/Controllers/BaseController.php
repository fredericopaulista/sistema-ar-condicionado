<?php

namespace App\Controllers;

class BaseController
{
    protected function view($path, $data = [])
    {
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
