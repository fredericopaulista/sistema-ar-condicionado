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
                        <td class="px-8 py-6 text-right flex justify-end gap-2">
                            <?php if ($c['status'] !== 'assinado'): ?>
                                <a href="/contratos/reenviar/<?= $c['id'] ?>" 
                                   class="flex items-center px-3 py-1.5 bg-emerald-600/20 text-emerald-400 hover:bg-emerald-600 hover:text-white rounded-lg text-xs font-bold transition-all border border-emerald-600/30" 
                                   title="Enviar link de assinatura por e-mail"
                                   onclick="return confirm('Deseja reenviar o link de assinatura para o cliente?')">
                                    <i class="fa-solid fa-paper-plane mr-1.5"></i> Enviar
                                </a>
                                <a href="/contratos/deletar/<?= $c['id'] ?>" 
                                   class="flex items-center px-3 py-1.5 bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white rounded-lg text-xs font-bold transition-all border border-red-500/20" 
                                   title="Remover este contrato da lista de espera"
                                   onclick="return confirm('Isso removerá o contrato desta lista e voltará o orçamento para o status Aprovado. Confirmar?')">
                                    <i class="fa-solid fa-trash mr-1.5"></i> Apagar
                                </a>
                            <?php else: ?>
                                <a href="/contratos/enviar-copia/<?= $c['id'] ?>" 
                                   class="flex items-center px-3 py-1.5 bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white rounded-lg text-xs font-bold transition-all border border-blue-600/30" 
                                   title="Enviar PDF assinado para o e-mail do cliente"
                                   onclick="return confirm('Deseja enviar uma cópia do contrato assinado para o cliente?')">
                                    <i class="fa-solid fa-envelope mr-1.5"></i> Enviar Cópia
                                </a>
                                <a href="/contratos/download/<?= $c['id'] ?>" 
                                   class="flex items-center px-3 py-1.5 bg-slate-700 text-slate-300 hover:bg-slate-600 hover:text-white rounded-lg text-xs font-bold transition-all border border-slate-600" 
                                   title="Baixar PDF assinado">
                                    <i class="fa-solid fa-download mr-1.5"></i> Download
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($c['token_publico'])): ?>
                            <a href="/p/<?= $c['token_publico'] ?>" target="_blank" 
                               class="flex items-center px-2 py-1.5 bg-slate-800 text-slate-400 hover:text-white rounded-lg transition-colors border border-slate-700" 
                               title="Visualizar no Portal">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
