<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Pedido;
use App\Models\Configuracao;

try {
    echo "--- Testing Config Model ---\n";
    $configModel = new Configuracao();
    $config = $configModel->all();
    echo "Config loaded. Keys: " . implode(', ', array_keys($config)) . "\n\n";

    echo "--- Testing Pedido Model ---\n";
    $pedidoModel = new Pedido();
    $pedidos = $pedidoModel->allWithDetails();
    echo "Pedidos loaded. Count: " . count($pedidos) . "\n";
    if (count($pedidos) > 0) {
        print_r($pedidos[0]);
    }

    echo "--- SUCCESS ---\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "TRACE:\n" . $e->getTraceAsString() . "\n";
}
