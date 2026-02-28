<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <p class="text-slate-400">Gerencie seus orçamentos e acompanhe o status de aprovação.</p>
    </div>
    <a href="/orcamentos/novo" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl transition-all shadow-lg shadow-blue-900/20 font-medium">
        <i class="fa-solid fa-plus mr-2"></i> Novo Orçamento
    </a>
</div>

<div class="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden shadow-xl">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-700/50">
            <tr>
                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Nº Orçamento</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Cliente</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Data</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Valor Final</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-700">
            <?php foreach ($orcamentos as $orc): ?>
            <tr class="hover:bg-slate-700/30 transition-colors">
                <td class="px-6 py-4 text-white font-mono text-sm"><?= $orc['numero'] ?></td>
                <td class="px-6 py-4 text-slate-300"><?= $orc['cliente_nome'] ?></td>
                <td class="px-6 py-4 text-slate-300"><?= date('d/m/Y', strtotime($orc['data_emissao'])) ?></td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold 
                        <?php 
                        switch($orc['status']) {
                            case 'criado': echo 'bg-blue-500/10 text-blue-500'; break;
                            case 'enviado': echo 'bg-indigo-500/10 text-indigo-500'; break;
                            case 'visualizado': echo 'bg-yellow-500/10 text-yellow-500'; break;
                            case 'aprovado': echo 'bg-emerald-500/10 text-emerald-500'; break;
                            case 'contrato_enviado': echo 'bg-purple-500/10 text-purple-500'; break;
                            case 'assinado': echo 'bg-cyan-500/10 text-cyan-500'; break;
                            case 'cancelado': echo 'bg-red-500/10 text-red-500'; break;
                        }
                        ?>">
                        <?= ucfirst(str_replace('_', ' ', $orc['status'])) ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-white font-bold">R$ <?= number_format($orc['valor_final'], 2, ',', '.') ?></td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="/p/<?= $orc['token_publico'] ?>" target="_blank" class="text-emerald-400 hover:text-emerald-300 p-2 rounded-lg hover:bg-emerald-500/10 transition-all" title="Ver Link Público">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="/orcamentos/deletar/<?= $orc['id'] ?>" onclick="return confirm('Tem certeza?')" class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/10 transition-all">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($orcamentos)): ?>
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                    <i class="fa-solid fa-file-invoice-dollar block text-4xl mb-4 opacity-20"></i>
                    Nenhum orçamento encontrado.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
