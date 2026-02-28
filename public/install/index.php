<?php
/**
 * SÓ AR BH - Web Installer
 * Automates system setup on cPanel/VPS.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$configFile = __DIR__ . '/../../config/config.php';
$sqlFile = __DIR__ . '/../../config/database.sql';

// Redirect if already installed (prevent accidental resets)
if (file_exists($configFile)) {
    die("Sistema já está instalado. Se deseja reinstalar, remova o arquivo config/config.php primeiro.");
}

$step = $_GET['step'] ?? 1;
$error = '';
$success = '';

// Step 3: Finalize Installation
if ($step == 3 && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $db_host = $_POST['db_host'];
    $db_name = $_POST['db_name'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $app_url = rtrim($_POST['app_url'], '/');
    $admin_email = $_POST['admin_email'];
    $admin_pass = $_POST['admin_pass'];

    try {
        // 1. Test Connection
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
        $db = new PDO($dsn, $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // 2. Import SQL
        if (!file_exists($sqlFile)) {
            throw new Exception("Arquivo database.sql não encontrado na pasta config/");
        }
        $sql = file_get_contents($sqlFile);
        $db->exec($sql);

        // 3. Update Admin User
        $hashedPass = password_hash($admin_pass, PASSWORD_BCRYPT);
        $stmt = $db->prepare("UPDATE usuarios SET email = ?, senha = ? WHERE id = 1");
        $stmt->execute([$admin_email, $hashedPass]);

        // 4. Create config.php
        $appKey = base64_encode(random_bytes(32));
        $configContent = "<?php\n\nreturn [\n";
        $configContent .= "    'db' => [\n";
        $configContent .= "        'host' => '$db_host',\n";
        $configContent .= "        'dbname' => '$db_name',\n";
        $configContent .= "        'user' => '$db_user',\n";
        $configContent .= "        'pass' => '$db_pass',\n";
        $configContent .= "        'charset' => 'utf8mb4'\n";
        $configContent .= "    ],\n";
        $configContent .= "    'app_url' => '$app_url',\n";
        $configContent .= "    'app_key' => '$appKey'\n";
        $configContent .= "];\n";

        if (file_put_contents($configFile, $configContent) === false) {
            throw new Exception("Não foi possível gravar o arquivo config/config.php. Verifique as permissões da pasta.");
        }

        $success = "Instalação concluída com sucesso!";
        $step = 4;
    } catch (Exception $e) {
        $error = "Erro: " . $e->getMessage();
        $step = 2;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador SÓ AR BH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

<div class="max-w-xl w-full glass rounded-3xl p-8 shadow-2xl">
    <div class="text-center mb-10">
        <div class="w-20 h-20 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl shadow-blue-500/20">
            <i class="fa-solid fa-server text-3xl text-white"></i>
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight">System Setup</h1>
        <p class="text-slate-400 mt-2">Instalador Automático v2.0</p>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-xl mb-6 text-sm flex items-start">
            <i class="fa-solid fa-triangle-exclamation mr-3 mt-1"></i>
            <span><?= $error ?></span>
        </div>
    <?php endif; ?>

    <!-- Progress Bar -->
    <div class="flex justify-between mb-10 relative">
        <div class="absolute top-1/2 left-0 w-full h-0.5 bg-slate-700 -translate-y-1/2 -z-10"></div>
        <?php for($i=1; $i<=3; $i++): ?>
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold <?= $step >= $i ? 'bg-blue-600 shadow-lg shadow-blue-500/30' : 'bg-slate-800' ?>">
                <?= $step > $i ? '<i class="fa-solid fa-check"></i>' : $i ?>
            </div>
        <?php endfor; ?>
    </div>

    <?php if ($step == 1): ?>
        <div class="space-y-6">
            <h2 class="text-xl font-bold border-l-4 border-blue-500 pl-4">Verificação de Requisitos</h2>
            <div class="space-y-4">
                <?php
                $checks = [
                    'Versão PHP 8.2+' => PHP_VERSION_ID >= 80200,
                    'Extensão PDO' => extension_loaded('pdo_mysql'),
                    'Permissão pasta config/' => is_writable(__DIR__ . '/../../config/'),
                ];
                $allClear = true;
                foreach($checks as $label => $pass): if(!$pass) $allClear = false; ?>
                    <div class="flex items-center justify-between p-4 bg-slate-900/50 rounded-2xl">
                        <span class="text-slate-300"><?= $label ?></span>
                        <i class="fa-solid <?= $pass ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-red-500' ?> text-lg"></i>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($allClear): ?>
                <a href="?step=2" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl transition-all shadow-xl shadow-blue-900/20">
                    Começar Instalação
                </a>
            <?php else: ?>
                <button disabled class="w-full bg-slate-700 text-slate-400 font-bold py-4 rounded-2xl cursor-not-allowed">
                    Corrija os erros para continuar
                </button>
            <?php endif; ?>
        </div>

    <?php elseif ($step == 2): ?>
        <form action="?step=3" method="POST" class="space-y-6">
            <h2 class="text-xl font-bold border-l-4 border-blue-500 pl-4">Configuração do Sistema</h2>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-2">Host Banco</label>
                        <input type="text" name="db_host" value="localhost" class="w-full mt-1 p-4 bg-slate-900 border border-slate-700 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 text-white" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-2">Nome Banco</label>
                        <input type="text" name="db_name" class="w-full mt-1 p-4 bg-slate-900 border border-slate-700 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 text-white" placeholder="ex: cpaneluser_db" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-2">Usuário Banco</label>
                        <input type="text" name="db_user" class="w-full mt-1 p-4 bg-slate-900 border border-slate-700 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 text-white" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-2">Senha Banco</label>
                        <input type="password" name="db_pass" class="w-full mt-1 p-4 bg-slate-900 border border-slate-700 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 text-white" required>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-2">URL Base do App</label>
                    <input type="url" name="app_url" value="<?= 'https://' . $_SERVER['HTTP_HOST'] ?>" class="w-full mt-1 p-4 bg-slate-900 border border-slate-700 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 text-white" required>
                </div>

                <hr class="border-slate-800 my-6">

                <h2 class="text-xl font-bold border-l-4 border-amber-500 pl-4">Acesso Administrativo</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-2">E-mail Admin</label>
                        <input type="email" name="admin_email" value="admin@soarbh.com.br" class="w-full mt-1 p-4 bg-slate-900 border border-slate-700 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 text-white" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest pl-2">Senha Admin</label>
                        <input type="password" name="admin_pass" class="w-full mt-1 p-4 bg-slate-900 border border-slate-700 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 text-white" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl transition-all shadow-xl shadow-blue-900/20">
                Instalar Agora
            </button>
        </form>

    <?php elseif ($step == 4): ?>
        <div class="text-center space-y-6">
            <div class="w-20 h-20 bg-emerald-500/20 rounded-full flex items-center justify-center mx-auto text-emerald-500 text-4xl shadow-lg shadow-emerald-500/10">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Instalação Finalizada!</h2>
                <p class="text-slate-400 mt-2">O sistema foi configurado com sucesso.</p>
            </div>
            
            <div class="bg-amber-500/10 border border-amber-500/50 text-amber-500 p-6 rounded-2xl text-left text-sm">
                <p class="font-bold flex items-center mb-2"><i class="fa-solid fa-triangle-exclamation mr-2"></i> SEGURANÇA:</p>
                Ação obrigatória: delete a pasta <strong>install/</strong> do seu servidor imediatamente para evitar acessos indesejados.
            </div>

            <a href="/login" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl transition-all shadow-xl shadow-blue-900/20">
                Ir para o Painel Login
            </a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
