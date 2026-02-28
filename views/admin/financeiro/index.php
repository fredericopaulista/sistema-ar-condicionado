<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white uppercase tracking-tight">Financeiro</h1>
            <p class="text-slate-400 mt-1">Gestão de entradas, saídas e fluxo de caixa.</p>
        </div>
        <button onclick="openModal('modalTransacao')" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-900/20 flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Nova Transação
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <span class="text-slate-400 text-sm font-medium">Total Entradas</span>
                <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-500">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-white">R$ <?= number_format($totalEntradas, 2, ',', '.') ?></h3>
        </div>

        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <span class="text-slate-400 text-sm font-medium">Total Saídas</span>
                <div class="w-10 h-10 bg-red-500/10 rounded-xl flex items-center justify-center text-red-500">
                    <i class="fa-solid fa-arrow-trend-down"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-white">R$ <?= number_format($totalSaidas, 2, ',', '.') ?></h3>
        </div>

        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <span class="text-slate-400 text-sm font-medium">Saldo Atual</span>
                <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-500">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold <?= $saldo >= 0 ? 'text-emerald-400' : 'text-red-400' ?>">
                R$ <?= number_format($saldo, 2, ',', '.') ?>
            </h3>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-slate-800 rounded-3xl border border-slate-700 shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-700/50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-white flex items-center">
                <i class="fa-solid fa-list-ul mr-3 text-blue-500"></i> Últimas Movimentações
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-900/50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Categoria</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Valor</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    <?php if (empty($transacoes)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            Nenhuma transação encontrada.
                        </td>
                    </tr>
                    <?php endif; ?>
                    
                    <?php foreach ($transacoes as $t): ?>
                    <tr class="hover:bg-slate-700/30 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-300">
                            <?= date('d/m/Y', strtotime($t['data_transacao'])) ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-white block"><?= htmlspecialchars($t['descricao']) ?></span>
                            <?php if ($t['orcamento_numero']): ?>
                            <span class="text-xs text-slate-500">Ref: <?= $t['orcamento_numero'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-900 text-slate-400 border border-slate-700">
                                <?= htmlspecialchars($t['categoria'] ?: 'Outros') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-bold <?= $t['tipo'] === 'entrada' ? 'text-emerald-400' : 'text-red-400' ?>">
                                <?= $t['tipo'] === 'entrada' ? '+' : '-' ?> R$ <?= number_format($t['valor'], 2, ',', '.') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="confirmDelete(<?= $t['id'] ?>)" class="text-slate-500 hover:text-red-400 transition-colors">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nova Transação -->
<div id="modalTransacao" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-800 w-full max-w-md rounded-3xl border border-slate-700 shadow-2xl overflow-hidden scale-up">
        <div class="p-6 border-b border-slate-700/50 flex justify-between items-center bg-slate-900/50">
            <h3 class="text-lg font-bold text-white uppercase tracking-tight">Nova Transação</h3>
            <button onclick="closeModal('modalTransacao')" class="text-slate-500 hover:text-white">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <form action="/financeiro/store" method="POST" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Descrição</label>
                <input type="text" name="descricao" required class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Valor</label>
                    <input type="text" name="valor" id="valor_mask" required class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tipo</label>
                    <select name="tipo" required class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Data</label>
                    <input type="date" name="data_transacao" value="<?= date('Y-m-d') ?>" required class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Categoria</label>
                    <input type="text" name="categoria" placeholder="Ex: Peças, Mão de obra" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modalTransacao')" class="flex-1 px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-bold rounded-xl transition-all">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-900/20">
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/imask"></script>
<script>
    IMask(document.getElementById('valor_mask'), {
        mask: 'num',
        blocks: {
            num: {
                mask: Number,
                thousandsSeparator: '.',
                radix: ',',
                mapToRadix: ['.']
            }
        }
    });

    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmDelete(id) {
        if (confirm('Deseja realmente excluir esta transação?')) {
            window.location.href = '/financeiro/delete/' + id;
        }
    }
</script>

<?php include __DIR__ . '/../layout/header.php'; ?>
