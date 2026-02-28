<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Painel' ?> - SÓ AR BH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-200">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 border-r border-slate-700 flex-shrink-0">
            <div class="p-6">
                <h1 class="text-xl font-bold text-white tracking-tight">SÓ AR BH</h1>
            </div>
            <nav class="mt-4 px-4 space-y-2">
                <a href="/dashboard" class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-700 hover:text-white rounded-xl transition-all group">
                    <i class="fa-solid fa-chart-line mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Dashboard
                </a>
                <a href="/clientes" class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-700 hover:text-white rounded-xl transition-all group">
                    <i class="fa-solid fa-users mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Clientes
                </a>
                <a href="/orcamentos" class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-700 hover:text-white rounded-xl transition-all group">
                    <i class="fa-solid fa-file-invoice-dollar mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Orçamentos
                </a>
                <a href="/configuracoes" class="flex items-center px-4 py-3 text-slate-300 hover:bg-slate-700 hover:text-white rounded-xl transition-all group">
                    <i class="fa-solid fa-gear mr-3 text-slate-500 group-hover:text-blue-400"></i>
                    Configurações
                </a>
                <div class="pt-4 border-t border-slate-700 mt-4">
                    <a href="/logout" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-xl transition-all">
                        <i class="fa-solid fa-right-from-bracket mr-3"></i>
                        Sair
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <header class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-white"><?= $title ?? 'Painel' ?></h2>
                <div class="flex items-center space-x-4">
                    <span class="text-slate-400 text-sm">Olá, <strong class="text-white"><?= $_SESSION['user_nome'] ?></strong></span>
                </div>
            </header>
