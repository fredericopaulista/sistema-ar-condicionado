<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl flex items-center">
        <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-500 mr-4">
            <i class="fa-solid fa-users text-xl"></i>
        </div>
        <div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Clientes</p>
            <h3 class="text-2xl font-bold text-white"><?= $totalClientes ?></h3>
        </div>
    </div>
    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl flex items-center border-l-4 border-l-emerald-500">
        <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-500 mr-4">
            <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
        </div>
        <div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Receita Fechada</p>
            <h3 class="text-2xl font-bold text-emerald-400">R$ <?= number_format($receita, 2, ',', '.') ?></h3>
        </div>
    </div>
    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl flex items-center border-l-4 border-l-amber-500">
        <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-500 mr-4">
            <i class="fa-solid fa-clock text-xl"></i>
        </div>
        <div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Receita Potencial</p>
            <h3 class="text-2xl font-bold text-amber-400">R$ <?= number_format($receitaPotencial, 2, ',', '.') ?></h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    <!-- Chart -->
    <div class="lg:col-span-2 bg-slate-800 p-8 rounded-3xl border border-slate-700 shadow-xl">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fa-solid fa-chart-line mr-3 text-blue-500"></i> Evolução Financeira
            </h3>
            <div class="flex items-center gap-4 text-xs font-bold uppercase tracking-widest">
                <div class="flex items-center text-emerald-500"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Entradas</div>
                <div class="flex items-center text-red-500"><span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span> Saídas</div>
            </div>
        </div>
        <div class="h-[300px]">
            <canvas id="financeChart"></canvas>
        </div>
    </div>

    <!-- Right Side Cards -->
    <div class="space-y-6">
        <div class="bg-slate-800 p-6 rounded-3xl border border-slate-700 shadow-xl">
            <h4 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-6">Taxas de Conversão</h4>
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 mr-4">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <div>
                            <p class="text-white font-bold text-sm">Aprovação</p>
                            <p class="text-slate-500 text-xs">Orçamentos aceitos</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-white"><?= round($taxaAprovacao) ?>%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center text-cyan-500 mr-4">
                            <i class="fa-solid fa-pen-nib"></i>
                        </div>
                        <div>
                            <p class="text-white font-bold text-sm">Assinatura</p>
                            <p class="text-slate-500 text-xs">Digital (Assinafy)</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-white"><?= round($taxaAssinatura) ?>%</span>
                </div>
            </div>
        </div>

        <a href="/financeiro" class="block bg-gradient-to-br from-blue-600 to-indigo-700 hover:from-blue-500 hover:to-indigo-600 text-white p-6 rounded-3xl transition-all shadow-xl shadow-blue-900/20 group relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-blue-100 text-xs font-bold uppercase tracking-wider mb-1">Fluxo de Caixa</p>
                <h3 class="text-xl font-bold">Gestão Financeira</h3>
                <p class="text-blue-200/70 text-xs mt-2">Clique para ver entradas e saídas</p>
            </div>
            <i class="fa-solid fa-arrow-right text-4xl absolute -right-4 -bottom-4 opacity-10 group-hover:translate-x-2 group-hover:-translate-y-2 transition-transform"></i>
        </a>
    </div>
</div>

<div class="bg-slate-800 p-8 rounded-3xl border border-slate-700 shadow-xl overflow-hidden">
    <div class="flex items-center justify-between mb-8">
        <h3 class="text-xl font-bold text-white">Atividades Recentes</h3>
        <a href="/orcamentos" class="text-blue-400 hover:text-blue-300 text-xs font-bold uppercase tracking-widest transition-colors">Ver todos</a>
    </div>
    <div class="space-y-4">
        <?php foreach ($atividades as $ativ): ?>
        <div class="flex items-center justify-between p-4 bg-slate-900/50 rounded-2xl border border-slate-700/50 hover:border-slate-600 transition-colors">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center mr-4">
                    <i class="fa-solid fa-clock-rotate-left text-slate-500"></i>
                </div>
                <div>
                    <p class="text-white font-medium text-sm"><?= $ativ['cliente'] ?></p>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-0.5">Orçamento <?= $ativ['numero'] ?></p>
                </div>
            </div>
            <span class="text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-widest border
                <?php 
                switch($ativ['status']) {
                    case 'criado': echo 'text-blue-400 border-blue-500/30 bg-blue-500/10'; break;
                    case 'aprovado': echo 'text-emerald-400 border-emerald-500/30 bg-emerald-500/10'; break;
                    case 'assinado': echo 'text-cyan-400 border-cyan-500/30 bg-cyan-500/10'; break;
                    default: echo 'text-slate-400 border-slate-500/30 bg-slate-500/10';
                }
                ?>">
                <?= str_replace('_', ' ', $ativ['status']) ?>
            </span>
        </div>
        <?php endforeach; ?>
        <?php if (empty($atividades)): ?>
            <div class="py-12 flex flex-col items-center justify-center text-slate-600">
                <i class="fa-solid fa-inbox text-4xl mb-3 opacity-20"></i>
                <p class="italic text-sm">Nenhuma atividade recente registrada.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('financeChart').getContext('2d');
    const monthlyData = <?= json_encode($monthlyStats) ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(i => {
                const parts = i.mes.split('-');
                return parts[1] + '/' + parts[0];
            }),
            datasets: [
                {
                    label: 'Entradas',
                    data: monthlyData.map(i => i.entradas),
                    borderColor: '#10b981',
                    backgroundColor: '#10b98120',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 4
                },
                {
                    label: 'Saídas',
                    data: monthlyData.map(i => i.saidas),
                    borderColor: '#ef4444',
                    backgroundColor: '#ef444420',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#ef4444',
                    pointRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#ffffff05' },
                    ticks: { 
                        color: '#64748b', 
                        font: { size: 10 },
                        callback: v => 'R$ ' + v.toLocaleString('pt-BR')
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 10 } }
                }
            }
        }
    });
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
