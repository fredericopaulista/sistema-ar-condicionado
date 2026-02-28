<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

// Simple Route Handler
function route($path, $controller, $method, $request_method = 'GET') {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // Adjust for subdirectories if needed (e.g. /sistema-ar/public/)
    $base_path = '/'; // Change this if running in a subdirectory
    $uri = str_replace($base_path, '/', $uri);
    $uri = '/' . ltrim($uri, '/');

    if ($uri === $path && $_SERVER['REQUEST_METHOD'] === $request_method) {
        $ctrl = new $controller();
        $ctrl->$method();
        exit;
    }
}

// Routes
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ClienteController;
use App\Controllers\UserController;
use App\Controllers\RoleController;
use App\Controllers\FinanceiroController;
use App\Controllers\PedidoController;

route('/login', AuthController::class, 'showLogin', 'GET');
route('/login', AuthController::class, 'login', 'POST');
route('/logout', AuthController::class, 'logout', 'GET');

route('/dashboard', DashboardController::class, 'index', 'GET');

route('/clientes', ClienteController::class, 'index', 'GET');
route('/clientes/novo', ClienteController::class, 'create', 'GET');
route('/clientes/novo', ClienteController::class, 'store', 'POST');

use App\Controllers\OrcamentoController;
route('/orcamentos', OrcamentoController::class, 'index', 'GET');
route('/orcamentos/novo', OrcamentoController::class, 'create', 'GET');
route('/orcamentos/novo', OrcamentoController::class, 'store', 'POST');

use App\Controllers\ContratoController;
route('/contratos', ContratoController::class, 'index', 'GET');

// User Routes
route('/usuarios', UserController::class, 'index', 'GET');
route('/usuarios/create', UserController::class, 'create', 'GET');
route('/usuarios/store', UserController::class, 'store', 'POST');

// Role Routes
route('/roles', RoleController::class, 'index', 'GET');
route('/roles/create', RoleController::class, 'create', 'GET');
route('/roles/store', RoleController::class, 'store', 'POST');

// Financeiro Routes
route('/financeiro', FinanceiroController::class, 'index', 'GET');
route('/financeiro/store', FinanceiroController::class, 'store', 'POST');

// Pedido Routes
route('/pedidos', PedidoController::class, 'index', 'GET');

// Handle dynamic routes
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('/^\/contratos\/refresh\/(\d+)$/', $uri, $matches)) {
    $ctrl = new ContratoController();
    $ctrl->refresh($matches[1]);
    exit;
}
if (preg_match('/^\/contratos\/reenviar\/(\d+)$/', $uri, $matches)) {
    $ctrl = new ContratoController();
    $ctrl->reenviar($matches[1]);
    exit;
}
if (preg_match('/^\/contratos\/enviar-copia\/(\d+)$/', $uri, $matches)) {
    $ctrl = new ContratoController();
    $ctrl->enviarCopia($matches[1]);
    exit;
}
if (preg_match('/^\/contratos\/download\/(\d+)$/', $uri, $matches)) {
    $ctrl = new ContratoController();
    $ctrl->download($matches[1]);
    exit;
}
if (preg_match('/^\/contratos\/deletar\/(\d+)$/', $uri, $matches)) {
    $ctrl = new ContratoController();
    $ctrl->deletar($matches[1]);
    exit;
}
if (preg_match('/^\/clientes\/editar\/(\d+)$/', $uri, $matches)) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $ctrl = new ClienteController();
        $ctrl->edit($matches[1]);
        exit;
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ctrl = new ClienteController();
        $ctrl->update($matches[1]);
        exit;
    }
}
if (preg_match('/^\/clientes\/deletar\/(\d+)$/', $uri, $matches)) {
    $ctrl = new ClienteController();
    $ctrl->delete($matches[1]);
    exit;
}

if (preg_match('/^\/orcamentos\/deletar\/(\d+)$/', $uri, $matches)) {
    $ctrl = new OrcamentoController();
    $ctrl->delete($matches[1]);
    exit;
}

// User Dynamic Routes
if (preg_match('/^\/usuarios\/edit\/(\d+)$/', $uri, $matches)) {
    $ctrl = new UserController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $ctrl->edit($matches[1]);
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ctrl->update($matches[1]);
    }
    exit;
}

if (preg_match('/^\/usuarios\/delete\/(\d+)$/', $uri, $matches)) {
    $ctrl = new UserController();
    $ctrl->delete($matches[1]);
    exit;
}

// Financeiro Dynamic Routes
if (preg_match('/^\/financeiro\/delete\/(\d+)$/', $uri, $matches)) {
    $ctrl = new FinanceiroController();
    $ctrl->delete($matches[1]);
    exit;
}

// Pedido Dynamic Routes
if (preg_match('/^\/pedidos\/view\/(\d+)$/', $uri, $matches)) {
    $ctrl = new PedidoController();
    $ctrl->view($matches[1]);
    exit;
}
if (preg_match('/^\/pedidos\/update\/(\d+)$/', $uri, $matches)) {
    $ctrl = new PedidoController();
    $ctrl->update($matches[1]);
    exit;
}

// Role Dynamic Routes
if (preg_match('/^\/roles\/edit\/(\d+)$/', $uri, $matches)) {
    $ctrl = new RoleController();
    $ctrl->edit($matches[1]);
    exit;
}
if (preg_match('/^\/roles\/update\/(\d+)$/', $uri, $matches)) {
    $ctrl = new RoleController();
    $ctrl->update($matches[1]);
    exit;
}
if (preg_match('/^\/roles\/delete\/(\d+)$/', $uri, $matches)) {
    $ctrl = new RoleController();
    $ctrl->delete($matches[1]);
    exit;
}

// Public Portal Routes
use App\Controllers\PortalController;
if (preg_match('/^\/p\/([a-f0-9\-]+)$/', $uri, $matches)) {
    // Note: Simple hex token regex
    $ctrl = new PortalController();
    $ctrl->viewByToken($matches[1]);
    exit;
}
if (preg_match('/^\/p\/([a-f0-9\-]+)\/aprovar$/', $uri, $matches)) {
    $ctrl = new PortalController();
    $ctrl->approve($matches[1]);
    exit;
}
if (preg_match('/^\/p\/([a-f0-9\-]+)\/solicitar-alteracao$/', $uri, $matches)) {
    $ctrl = new PortalController();
    $ctrl->requestChange($matches[1]);
    exit;
}

// Webhook Route (No longer using external signature services)

// Settings Routes
use App\Controllers\SettingsController;
route('/configuracoes', SettingsController::class, 'index');
route('/configuracoes/update', SettingsController::class, 'update', 'POST');
route('/configuracoes/test-email', SettingsController::class, 'testEmail', 'GET');

// Default Route
if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/') {
    header('Location: /login');
    exit;
}

// 404
http_response_code(404);
echo "404 - Página não encontrada";
