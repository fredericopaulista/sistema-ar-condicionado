<?php include __DIR__ . '/layout/header.php'; ?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-lg">
        <p class="text-slate-400 text-sm font-medium mb-1">Total Clientes</p>
        <h3 class="text-3xl font-bold text-white"><?= $totalClientes ?></h3>
    </div>
    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-lg">
        <p class="text-slate-400 text-sm font-medium mb-1">Total Orçamentos</p>
        <h3 class="text-3xl font-bold text-white"><?= $totalOrcamentos ?></h3>
    </div>
    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-lg border-l-4 border-l-emerald-500">
        <p class="text-slate-400 text-sm font-medium mb-1">Receita Fechada</p>
        <h3 class="text-3xl font-bold text-emerald-400">R$ <?= number_format($receita, 2, ',', '.') ?></h3>
    </div>
    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-lg border-l-4 border-l-blue-500">
        <p class="text-slate-400 text-sm font-medium mb-1">Receita Potencial</p>
        <h3 class="text-3xl font-bold text-blue-400">R$ <?= number_format($receitaPotencial, 2, ',', '.') ?></h3>
    </div>
</div>

<!-- Conversion Rates -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-lg flex items-center">
        <div class="w-16 h-16 rounded-full border-4 border-emerald-500/30 border-t-emerald-500 flex items-center justify-center mr-6">
            <span class="text-lg font-bold text-white"><?= round($taxaAprovacao) ?>%</span>
        </div>
        <div>
            <h4 class="text-white font-bold text-lg">Taxa de Aprovação</h4>
            <p class="text-slate-400 text-sm">Orçamentos aceitos pelo cliente</p>
        </div>
    </div>
    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-lg flex items-center">
        <div class="w-16 h-16 rounded-full border-4 border-cyan-500/30 border-t-cyan-500 flex items-center justify-center mr-6">
            <span class="text-lg font-bold text-white"><?= round($taxaAssinatura) ?>%</span>
        </div>
        <div>
            <h4 class="text-white font-bold text-lg">Taxa de Assinatura</h4>
            <p class="text-slate-400 text-sm">Contratos assinados via Assinafy</p>
        </div>
    </div>
</div>

<div class="bg-slate-800 p-8 rounded-2xl border border-slate-700 shadow-lg">
    <h3 class="text-xl font-bold text-white mb-6">Atividades Recentes</h3>
    <div class="space-y-4">
        <?php foreach ($atividades as $ativ): ?>
        <div class="flex items-center justify-between p-4 bg-slate-700/30 rounded-xl border border-slate-700/50">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center mr-4">
                    <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                </div>
                <div>
                    <p class="text-white font-medium text-sm"><?= $ativ['cliente'] ?></p>
                    <p class="text-slate-400 text-xs">Orçamento <?= $ativ['numero'] ?></p>
                </div>
            </div>
            <span class="text-xs px-2 py-1 rounded-md font-bold uppercase tracking-wider
                <?php 
                switch($ativ['status']) {
                    case 'criado': echo 'text-blue-400'; break;
                    case 'aprovado': echo 'text-emerald-400'; break;
                    case 'assinado': echo 'text-cyan-400'; break;
                    default: echo 'text-slate-400';
                }
                ?>">
                <?= str_replace('_', ' ', $ativ['status']) ?>
            </span>
        </div>
        <?php endforeach; ?>
        <?php if (empty($atividades)): ?>
            <p class="text-slate-500 italic text-sm text-center py-4">Nenhuma atividade recente registrada.</p>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
