<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= htmlspecialchars($global_company['company_name'] ?? 'SÓ AR BH') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 space-y-6 bg-slate-800 rounded-2xl shadow-2xl border border-slate-700">
        <div class="text-center">
            <?php if (!empty($global_company['company_logo'])): ?>
                <img src="/<?= $global_company['company_logo'] ?>" class="h-16 w-auto object-contain brightness-0 invert mx-auto mb-4" alt="<?= htmlspecialchars($global_company['company_name']) ?>">
            <?php else: ?>
                <h1 class="text-3xl font-bold text-white mb-2"><?= htmlspecialchars($global_company['company_name'] ?? 'SÓ AR BH') ?></h1>
            <?php endif; ?>
            <p class="text-slate-400">Sistema de Orçamentos</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-500/10 border border-red-500/50 text-red-500 p-4 rounded-lg text-sm text-center">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">E-mail</label>
                <input type="email" name="email" required 
                    class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder-slate-500"
                    placeholder="admin@soarbh.com.br">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Senha</label>
                <input type="password" name="senha" required 
                    class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder-slate-500"
                    placeholder="••••••••">
            </div>

            <button type="submit" 
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-900/20 active:scale-[0.98]">
                Entrar no Painel
            </button>
        </form>

        <p class="text-center text-slate-500 text-xs">
            © <?= date('Y') ?> <?= htmlspecialchars($global_company['company_name'] ?? 'SÓ AR BH') ?>. Todos os direitos reservados.
        </p>
    </div>
</body>
</html>
