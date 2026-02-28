<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-white">Pedidos (Execução)</h1>
        <p class="text-slate-400 mt-1">Monitore a execução dos serviços de contratos assinados.</p>
    </div>
</div>

<div class="bg-slate-800 rounded-3xl border border-slate-700 shadow-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-900/50 border-b border-slate-700 text-slate-400 text-xs font-bold uppercase tracking-widest">
                    <th class="px-8 py-5">Nº Pedido</th>
                    <th class="px-8 py-5">Orçamento</th>
                    <th class="px-8 py-5">Cliente</th>
                    <th class="px-8 py-5">Valor</th>
                    <th class="px-8 py-5 text-center">Status</th>
                    <th class="px-8 py-5 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                <?php foreach ($pedidos as $p): ?>
                <tr class="hover:bg-slate-700/30 transition-colors">
                    <td class="px-8 py-5 font-mono text-white text-sm">#<?= str_pad($p['id'], 5, '0', STR_PAD_LEFT) ?></td>
                    <td class="px-8 py-5">
                        <span class="text-slate-400 text-xs block uppercase font-bold tracking-widest">Original</span>
                        <span class="text-white text-sm font-medium"><?= $p['orcamento_numero'] ?></span>
                    </td>
                    <td class="px-8 py-5">
                        <span class="text-white text-sm font-semibold"><?= $p['cliente_nome'] ?></span>
                        <span class="text-slate-500 text-xs block">Assinado em <?= date('d/m/Y', strtotime($p['data_pedido'])) ?></span>
                    </td>
                    <td class="px-8 py-5">
                        <span class="text-white font-bold">R$ <?= number_format($p['valor_final'], 2, ',', '.') ?></span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <?php 
                        $statusClass = '';
                        $statusLabel = '';
                        switch($p['status']) {
                            case 'pendente': 
                                $statusClass = 'bg-amber-500/10 text-amber-500 border-amber-500/30'; 
                                $statusLabel = 'Aguardando Início';
                                break;
                            case 'em_andamento': 
                                $statusClass = 'bg-blue-500/10 text-blue-500 border-blue-500/30'; 
                                $statusLabel = 'Em Execução';
                                break;
                            case 'concluido': 
                                $statusClass = 'bg-emerald-500/10 text-emerald-500 border-emerald-500/30'; 
                                $statusLabel = 'Finalizado';
                                break;
                            case 'cancelado': 
                                $statusClass = 'bg-red-500/10 text-red-500 border-red-500/30'; 
                                $statusLabel = 'Cancelado';
                                break;
                        }
                        ?>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border <?= $statusClass ?>">
                            <?= $statusLabel ?>
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <a href="/pedidos/view/<?= $p['id'] ?>" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-900 text-blue-400 hover:bg-blue-600 hover:text-white transition-all shadow-lg" title="Ver Detalhes">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($pedidos)): ?>
                <tr>
                    <td colspan="6" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-slate-900 rounded-3xl flex items-center justify-center text-slate-700 mb-4 border border-slate-700/50">
                                <i class="fa-solid fa-file-contract text-3xl"></i>
                            </div>
                            <h3 class="text-white font-bold">Nenhum pedido gerado ainda</h3>
                            <p class="text-slate-500 text-sm mt-1">Os pedidos aparecem aqui automaticamente após a assinatura dos contratos.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
