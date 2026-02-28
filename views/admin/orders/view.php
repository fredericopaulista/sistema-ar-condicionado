<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center">
            <a href="/pedidos" class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 hover:text-white mr-4 transition-all">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Pedido #<?= str_pad($pedido['id'], 5, '0', STR_PAD_LEFT) ?></h1>
                <p class="text-slate-400 text-sm">Vinculado ao Orçamento <span class="text-blue-400 font-mono font-bold"><?= $pedido['orcamento_numero'] ?></span></p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold uppercase tracking-widest text-slate-500 mr-2">Status da Execução:</span>
            <?php 
            $statusColors = [
                'pendente' => 'text-amber-400 bg-amber-400/10 border-amber-400/30',
                'em_andamento' => 'text-blue-400 bg-blue-400/10 border-blue-400/30',
                'concluido' => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/30',
                'cancelado' => 'text-red-400 bg-red-400/10 border-red-400/30'
            ];
            ?>
            <span class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest border <?= $statusColors[$pedido['status']] ?>">
                <?= str_replace('_', ' ', $pedido['status']) ?>
            </span>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
    <div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 px-6 py-4 rounded-2xl mb-8 flex items-center">
        <i class="fa-solid fa-circle-check mr-3 text-xl"></i>
        <span>Pedido atualizado com sucesso!</span>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Column: Details & Notes -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Client & Service Info -->
            <div class="bg-slate-800 rounded-3xl border border-slate-700 shadow-xl overflow-hidden">
                <div class="p-8 border-b border-slate-700/50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fa-solid fa-user-gear mr-3 text-blue-500"></i> Informações do Cliente
                    </h3>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Nome / Empresa</p>
                        <p class="text-white font-semibold"><?= $pedido['cliente_nome'] ?></p>
                        <div class="mt-4 space-y-2">
                            <p class="text-slate-400 text-sm"><i class="fa-solid fa-envelope w-5 text-slate-600"></i> <?= $pedido['cliente_email'] ?></p>
                            <p class="text-slate-400 text-sm"><i class="fa-solid fa-phone w-5 text-slate-600"></i> <?= $pedido['cliente_telefone'] ?></p>
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Endereço de Execução</p>
                        <p class="text-white text-sm line-height-relaxed">
                            <?= $pedido['cliente_endereco'] ?>, <?= $pedido['cliente_numero'] ?><br>
                            <?= $pedido['cliente_bairro'] ?> - Belo Horizonte/MG
                        </p>
                    </div>
                </div>
            </div>

            <!-- Technical Notes (Editable) -->
            <form action="/pedidos/update/<?= $pedido['id'] ?>" method="POST" class="bg-slate-800 rounded-3xl border border-slate-700 shadow-xl overflow-hidden">
                <div class="p-8 border-b border-slate-700/50 flex items-center justify-between bg-slate-900/30">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fa-solid fa-clipboard-list mr-3 text-amber-500"></i> Notas Técnicas e Planejamento
                    </h3>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-blue-900/20">
                        Salvar Alterações
                    </button>
                </div>
                <div class="p-8 space-y-6">
                    <div>
                        <label class="block text-slate-400 text-xs font-bold uppercase tracking-widest mb-3">Status da Obra/Serviço</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <?php 
                            $options = [
                                'pendente' => ['label' => 'Pendente', 'color' => 'peer-checked:bg-amber-500 peer-checked:text-white'],
                                'em_andamento' => ['label' => 'Em Execução', 'color' => 'peer-checked:bg-blue-600 peer-checked:text-white'],
                                'concluido' => ['label' => 'Concluído', 'color' => 'peer-checked:bg-emerald-600 peer-checked:text-white'],
                                'cancelado' => ['label' => 'Cancelado', 'color' => 'peer-checked:bg-red-600 peer-checked:text-white'],
                            ];
                            foreach($options as $val => $opt): ?>
                            <label class="cursor-pointer group">
                                <input type="radio" name="status" value="<?= $val ?>" class="hidden peer" <?= $pedido['status'] === $val ? 'checked' : '' ?>>
                                <div class="px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-center text-xs font-bold text-slate-400 transition-all border-dashed peer-checked:border-solid <?= $opt['color'] ?>">
                                    <?= $opt['label'] ?>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-400 text-xs font-bold uppercase tracking-widest mb-3">Observações Adicionais (Instalação, Materiais, Prazos)</label>
                        <textarea name="observacoes_tecnicas" rows="6" class="w-full px-5 py-4 bg-slate-900 border border-slate-700 rounded-2xl text-slate-300 outline-none focus:ring-2 focus:ring-blue-500 transition-all resize-none" placeholder="Ex: Agendado para terça-feira às 09:00. Necessário levar escada de 6 metros..."><?= htmlspecialchars($pedido['observacoes_tecnicas'] ?? '') ?></textarea>
                    </div>
                </div>
            </form>

            <!-- Items table -->
            <div class="bg-slate-800 rounded-3xl border border-slate-700 shadow-xl overflow-hidden">
                <div class="p-8 border-b border-slate-700/50">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fa-solid fa-list-check mr-3 text-emerald-500"></i> Itens do Serviço
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-700/50">
                                <th class="px-8 py-4">Serviço/Produto</th>
                                <th class="px-8 py-4 text-center">Qtd</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/30">
                            <?php foreach($orcamento['itens'] as $i): ?>
                            <tr>
                                <td class="px-8 py-5">
                                    <span class="text-white font-medium block"><?= $i['categoria'] ?></span>
                                    <span class="text-slate-500 text-xs"><?= $i['descricao'] ?></span>
                                </td>
                                <td class="px-8 py-5 text-center text-white font-bold"><?= $i['quantidade'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Column: Summary & History -->
        <div class="space-y-8">
            <div class="bg-blue-600 rounded-3xl p-8 text-white shadow-xl shadow-blue-900/20 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mb-1">Valor Total do Pedido</p>
                    <h2 class="text-4xl font-extrabold tracking-tight">R$ <?= number_format($pedido['valor_final'], 2, ',', '.') ?></h2>
                    <div class="mt-6 pt-6 border-t border-blue-400/30 flex justify-between items-center">
                        <div class="text-[10px] uppercase font-bold text-blue-200 tracking-widest">Forma de Pagamento</div>
                        <div class="text-sm font-bold"><?= $pedido['forma_pagamento'] ?></div>
                    </div>
                </div>
                <i class="fa-solid fa-receipt text-9xl absolute -right-8 -bottom-8 text-white/10 group-hover:scale-110 transition-transform duration-500"></i>
            </div>

            <div class="bg-slate-800 rounded-3xl border border-slate-700 p-8">
                <h4 class="text-white font-bold mb-4 flex items-center uppercase text-xs tracking-widest">
                    <i class="fa-solid fa-history mr-2 text-slate-500"></i> Histórico do Orçamento
                </h4>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-slate-900 border border-slate-700 flex items-center justify-center text-emerald-500">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </div>
                            <div class="w-px h-full bg-slate-700 my-2"></div>
                        </div>
                        <div>
                            <p class="text-white text-xs font-bold">Orçamento Aprovado</p>
                            <p class="text-slate-500 text-[10px]"><?= date('d/m/Y', strtotime($pedido['data_emissao'])) ?> (Original)</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-slate-900 border border-slate-700 flex items-center justify-center text-blue-500">
                                <i class="fa-solid fa-signature text-[10px]"></i>
                            </div>
                            <div class="w-px h-full bg-slate-700 my-2"></div>
                        </div>
                        <div>
                            <p class="text-white text-xs font-bold">Contrato Assinado</p>
                            <p class="text-slate-500 text-[10px]"><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
