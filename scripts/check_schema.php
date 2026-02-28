<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Services\Database;

try {
    $db = Database::getInstance();
    
    $tables = ['orcamentos', 'clientes', 'pedidos'];
    
    foreach ($tables as $table) {
        echo "--- Table: $table ---\n";
        $stmt = $db->query("DESCRIBE $table");
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            echo "{$col['Field']} ({$col['Type']})\n";
        }
        echo "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
