<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="space-y-6">
    <div class="bg-slate-800 rounded-2xl border border-slate-700 shadow-xl overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-white">Contratos em Assinatura</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-8 py-4">Orçamento</th>
                        <th class="px-8 py-4">Cliente</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4">Última Atualização</th>
                        <th class="px-8 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    <?php if (empty($contratos)): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-slate-500 italic">
                            Nenhum contrato enviado para assinatura ainda.
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php foreach ($contratos as $c): ?>
                    <tr class="hover:bg-slate-700/30 transition-colors">
                        <td class="px-8 py-6">
                            <span class="font-mono text-blue-400"><?= $c['numero'] ?></span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-white font-medium"><?= $c['cliente_nome'] ?></div>
                            <div class="text-slate-500 text-xs"><?= $c['cliente_email'] ?></div>
                        </td>
                        <td class="px-8 py-6">
                            <?php if ($c['status'] === 'assinado'): ?>
                                <span class="px-3 py-1 bg-emerald-500/10 text-emerald-500 rounded-full text-xs font-bold flex items-center w-fit">
                                    <i class="fa-solid fa-check-double mr-1"></i> Assinado
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-amber-500/10 text-amber-500 rounded-full text-xs font-bold flex items-center w-fit">
                                    <i class="fa-solid fa-clock mr-1"></i> Aguardando
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-6 text-slate-400 text-sm">
                            <?= date('d/m/Y H:i', strtotime($c['updated_at'] ?? $c['created_at'])) ?>
                        </td>
                        <td class="px-8 py-6 text-right space-x-3">
                            <?php if ($c['status'] !== 'assinado'): ?>
                            <a href="/contratos/refresh/<?= $c['id'] ?>" class="text-blue-400 hover:text-blue-300 transition-colors" title="Sincronizar Status">
                                <i class="fa-solid fa-rotate"></i>
                            </a>
                            <?php endif; ?>
                            <a href="<?= $c['link_assinatura'] ?>" target="_blank" class="text-slate-400 hover:text-white transition-colors" title="Visualizar Contrato">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
