<?php
/**
 * Script de atualização do banco de dados (Remoto)
 * Adiciona as colunas necessárias para o sistema de assinatura customizado.
 */

$projectRoot = '/Users/fredmoura/Downloads/sistema-ar';
$config = require $projectRoot . '/config/config.php';

try {
    $host = $config['db']['host'];
    $dbname = $config['db']['dbname'];
    $user = $config['db']['user'];
    $pass = $config['db']['pass'];
    $charset = $config['db']['charset'];

    echo "Conectando ao banco de dados REMOTO: $host...\n";

    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $db = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "Conectado com sucesso!\n";

    $sql = "ALTER TABLE orcamentos 
            ADD COLUMN IF NOT EXISTS assinatura_imagem VARCHAR(255) NULL, 
            ADD COLUMN IF NOT EXISTS assinatura_ip VARCHAR(45) NULL, 
            ADD COLUMN IF NOT EXISTS assinatura_data DATETIME NULL, 
            ADD COLUMN IF NOT EXISTS assinatura_hash VARCHAR(64) NULL";
            
    echo "Executando alterações na tabela 'orcamentos'...\n";
    $db->exec($sql);
    
    echo "--------------------------------------------------\n";
    echo "BANCO DE DADOS ATUALIZADO COM SUCESSO!\n";
    echo "As colunas para a assinatura digital foram criadas.\n";
    echo "--------------------------------------------------\n";

} catch (Exception $e) {
    echo "\nERRO AO ATUALIZAR BANCO REMOTO:\n";
    echo $e->getMessage() . "\n";
    echo "\nVerifique se o seu IP está liberado no firewall do servidor remoto.\n";
    exit(1);
}
