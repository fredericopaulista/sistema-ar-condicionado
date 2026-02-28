<?php
/**
 * SÓ AR BH - Web Installer (AI Powered)
 * Automates system setup and adapts to any business niche using OpenAI.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$configFile = __DIR__ . '/../../config/config.php';
$sqlFile = __DIR__ . '/../../config/database.sql';

if (file_exists($configFile)) {
    die("Sistema já está instalado. Remova config/config.php para reinstalar.");
}

$step = $_GET['step'] ?? 1;
$error = '';
$success = '';

if ($step == 3 && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $db_host = $_POST['db_host'];
    $db_name = $_POST['db_name'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass'];
    $openai_key = $_POST['openai_key'];
    $business_type = $_POST['business_type'];
    $app_url = rtrim($_POST['app_url'], '/');
    $admin_email = $_POST['admin_email'];
    $admin_pass = $_POST['admin_pass'];

    try {
        // 1. Test connection
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
        $db = new PDO($dsn, $db_user, $db_pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        // 2. Import SQL
        if (!file_exists($sqlFile)) throw new Exception("Arquivo database.sql não encontrado.");
        $sql = file_get_contents($sqlFile);
        $db->exec($sql);

        // 3. Update Admin
        $hashedPass = password_hash($admin_pass, PASSWORD_BCRYPT);
        $stmt = $db->prepare("UPDATE usuarios SET email = ?, senha = ? WHERE id = 1");
        $stmt->execute([$admin_email, $hashedPass]);

        // 4. Generate AI Catalog (OpenAI)
        if (!empty($openai_key) && !empty($business_type)) {
            $prompt = "Atue como um consultor de negócios experiente. O usuário está instalando um sistema de gestão para o segmento: '$business_type'. 
            Gere uma lista de 10 serviços ou produtos essenciais para este nicho.
            Retorne APENAS um JSON puro (sem markdown) no seguinte formato de array:
            [{\"categoria\": \"NOME_CATEGORIA\", \"descricao\": \"DESCRIÇÃO_CURTA\", \"valor\": 0.00}]";

            $ch = curl_init('https://api.openai.com/v1/chat/completions');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'model' => 'gpt-3.5-turbo',
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'temperature' => 0.7
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $openai_key
            ]);
            
            $response = curl_exec($ch);
            $respData = json_decode($response, true);
            
            if (isset($respData['choices'][0]['message']['content'])) {
                $catalog = json_decode(trim($respData['choices'][0]['message']['content']), true);
                if (is_array($catalog)) {
                    $stmt = $db->prepare("INSERT INTO catalogo_servicos (categoria, descricao, valor_sugerido) VALUES (?, ?, ?)");
                    foreach ($catalog as $item) {
                        $stmt->execute([$item['categoria'], $item['descricao'], $item['valor']]);
                    }
                }
            }
            curl_close($ch);
        }

        // 5. Create config.php
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
        $configContent .= "    'app_key' => '$appKey',\n";
        $configContent .= "    'openai_key' => '$openai_key'\n";
        $configContent .= "];\n";

        file_put_contents($configFile, $configContent);
        $success = "Instalação concluída!";
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
    <meta charset="UTF-8"><title>Instalador Inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>body{background:#0f172a;color:#f8fafc;}.glass{background:rgba(30,41,59,0.7);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.1);}</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
<div class="max-w-2xl w-full glass rounded-3xl p-8 shadow-2xl">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4"><i class="fa-solid fa-robot text-2xl text-white"></i></div>
        <h1 class="text-2xl font-bold">Configuração do Sistema</h1>
    </div>

    <?php if ($error): ?><div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-xl mb-6 text-sm"><?= $error ?></div><?php endif; ?>

    <?php if ($step == 1): ?>
        <div class="space-y-6">
            <h2 class="font-bold text-lg">1. Verificação de Requisitos</h2>
            <?php
            $checks = ['PHP 8.2+' => PHP_VERSION_ID >= 80200, 'PDO MySQL' => extension_loaded('pdo_mysql'), 'Pasta Config Writable' => is_writable(__DIR__ . '/../../config/')];
            $allClear = true;
            foreach($checks as $l => $p): if(!$p) $allClear = false; ?>
                <div class="flex justify-between p-3 bg-slate-900/50 rounded-xl"><span><?= $l ?></span><i class="fa-solid <?= $p ? 'fa-check text-emerald-500' : 'fa-xmark text-red-500' ?>"></i></div>
            <?php endforeach; ?>
            <a href="?step=2" class="block w-full text-center bg-blue-600 py-3 rounded-xl font-bold">Continuar</a>
        </div>

    <?php elseif ($step == 2): ?>
        <form action="?step=3" method="POST" class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-[10px] uppercase font-bold text-slate-500 ml-2">Host Banco</label><input type="text" name="db_host" value="localhost" class="w-full mt-1 p-3 bg-slate-900 border border-slate-700 rounded-xl outline-none focus:ring-1 focus:ring-blue-500" required></div>
                <div><label class="text-[10px] uppercase font-bold text-slate-500 ml-2">Nome Banco</label><input type="text" name="db_name" class="w-full mt-1 p-3 bg-slate-900 border border-slate-700 rounded-xl outline-none focus:ring-1 focus:ring-blue-500" required></div>
                <div><label class="text-[10px] uppercase font-bold text-slate-500 ml-2">Usuário Banco</label><input type="text" name="db_user" class="w-full mt-1 p-3 bg-slate-900 border border-slate-700 rounded-xl outline-none focus:ring-1 focus:ring-blue-500" required></div>
                <div><label class="text-[10px] uppercase font-bold text-slate-500 ml-2">Senha Banco</label><input type="password" name="db_pass" class="w-full mt-1 p-3 bg-slate-900 border border-slate-700 rounded-xl outline-none focus:ring-1 focus:ring-blue-500" required></div>
            </div>

            <div class="bg-blue-500/5 p-6 rounded-2xl border border-blue-500/20">
                <h3 class="text-blue-400 font-bold mb-4 flex items-center text-sm"><i class="fa-solid fa-wand-magic-sparkles mr-2"></i> Adaptação Inteligente (OpenAI)</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-500 ml-2">Sua OpenAI API Key</label>
                        <input type="password" name="openai_key" placeholder="sk-..." class="w-full mt-1 p-3 bg-slate-950 border border-slate-700 rounded-xl outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-500 ml-2">Segmento de Negócio</label>
                        <input type="text" name="business_type" placeholder="Ex: Ar Condicionado, Oficina, Advocacia..." class="w-full mt-1 p-3 bg-slate-950 border border-slate-700 rounded-xl outline-none focus:ring-1 focus:ring-blue-500">
                        <p class="text-[10px] text-slate-500 mt-2">A IA criará um catálogo de serviços inicial baseado nesse nicho.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-[10px] uppercase font-bold text-slate-500 ml-2">E-mail Admin</label><input type="email" name="admin_email" value="admin@exemplo.com" class="w-full mt-1 p-3 bg-slate-900 border border-slate-700 rounded-xl outline-none" required></div>
                <div><label class="text-[10px] uppercase font-bold text-slate-500 ml-2">Senha Admin</label><input type="password" name="admin_pass" class="w-full mt-1 p-3 bg-slate-900 border border-slate-700 rounded-xl outline-none" required></div>
            </div>

            <input type="hidden" name="app_url" value="<?= 'https://' . $_SERVER['HTTP_HOST'] ?>">
            <button type="submit" onclick="this.innerHTML='<i class=\'fa-solid fa-circle-notch fa-spin mr-2\'></i> Processando com IA...'; this.classList.add('opacity-50')" class="w-full bg-blue-600 hover:bg-blue-700 py-4 rounded-2xl font-bold shadow-xl shadow-blue-900/20">Finalizar Instalação</button>
        </form>

    <?php elseif ($step == 4): ?>
        <div class="text-center space-y-6">
            <i class="fa-solid fa-circle-check text-emerald-500 text-5xl"></i>
            <h2 class="text-2xl font-bold">Tudo Pronto!</h2>
            <p class="text-slate-400">O sistema foi instalado e o catálogo de serviços foi gerado pela IA.</p>
            <div class="bg-amber-500/10 border border-amber-500/50 text-amber-500 p-4 rounded-xl text-left text-sm">
                <strong>Ação Obrigatória:</strong> Delete a pasta <strong>public/install/</strong> agora.
            </div>
            <a href="/login" class="block w-full bg-blue-600 py-4 rounded-xl font-bold">Acessar Painel</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
