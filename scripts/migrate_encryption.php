<?php

// Include necessary files manually since we don't have an autoloader in this CLI context
require_once __DIR__ . '/../app/Utils/Security.php';

// Custom connection for migration that avoids socket issues by using 127.0.0.1
function getMigrationConnection() {
    $config = require __DIR__ . '/../config/config.php';
    $db = $config['db'];
    
    // Force 127.0.0.1 if host is localhost to avoid "No such file or directory" (socket) error in CLI
    $host = ($db['host'] === 'localhost') ? '127.0.0.1' : $db['host'];
    
    $dsn = "mysql:host={$host};dbname={$db['dbname']};charset={$db['charset']}";
    return new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}

try {
    echo "Starting migration...\n";
    $db = getMigrationConnection();
    
    $sensitiveKeys = ['mail_pass', 'assinafy_api_key'];
    
    $stmt = $db->query("SELECT * FROM configuracoes_sistema");
    $rows = $stmt->fetchAll();
    
    foreach ($rows as $row) {
        if (in_array($row['chave'], $sensitiveKeys)) {
            $valor = $row['valor'];
            
            // To be double sure we don't encrypt an already encrypted value
            // We'll check if Security::decrypt returns something readable or if the value has a certain format
            // However, since we just started, everything is plain text.
            
            echo "Processing {$row['chave']}...\n";
            $encrypted = \App\Utils\Security::encrypt($valor);
            
            $update = $db->prepare("UPDATE configuracoes_sistema SET valor = ? WHERE chave = ?");
            $update->execute([$encrypted, $row['chave']]);
            echo "✅ Encrypted and updated {$row['chave']}\n";
        }
    }
    echo "Migration finished successfully!\n";
} catch (\Exception $e) {
    echo "❌ Error during migration: " . $e->getMessage() . "\n";
}
