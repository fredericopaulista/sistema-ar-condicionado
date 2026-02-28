<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-6xl mx-auto">
    <form action="/orcamentos/novo" method="POST" id="quoteForm">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Basic Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-slate-800 rounded-2xl border border-slate-700 shadow-xl p-8">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                        <i class="fa-solid fa-circle-info mr-2 text-blue-500"></i> Informações Básicas
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Selecione o Cliente</label>
                            <select name="cliente_id" required class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Escolha um cliente...</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?= $cliente['id'] ?>"><?= $cliente['nome'] ?> (<?= $cliente['email'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Número do Orçamento</label>
                            <input type="text" name="numero" value="<?= $proximoNumero ?>" readonly class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-xl text-slate-400 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Data de Emissão</label>
                            <input type="date" name="data_emissao" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Validade (Dias)</label>
                            <input type="number" name="validade_dias" value="7" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Items Section -->
                <div class="bg-slate-800 rounded-2xl border border-slate-700 shadow-xl p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fa-solid fa-list-check mr-2 text-blue-500"></i> Itens do Orçamento
                        </h3>
                        <button type="button" onclick="addItem()" class="text-blue-400 hover:text-blue-300 text-sm font-semibold">
                            <i class="fa-solid fa-plus mr-1"></i> Adicionar Item
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left" id="itemsTable">
                            <thead class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="pb-4 pr-4">Categoria</th>
                                    <th class="pb-4 pr-4">Descrição</th>
                                    <th class="pb-4 pr-4 w-20">Qtd</th>
                                    <th class="pb-4 pr-4 w-32">Unitário (R$)</th>
                                    <th class="pb-4 w-10"></th>
                                </tr>
                            </thead>
                            <tbody class="space-y-4">
                                <tr class="item-row">
                                    <td class="pb-4 pr-4">
                                        <select name="itens[categoria][]" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                                            <option value="Instalação">Instalação</option>
                                            <option value="Manutenção">Manutenção</option>
                                            <option value="Higienização">Higienização</option>
                                            <option value="Peças">Peças</option>
                                            <option value="Outros">Outros</option>
                                        </select>
                                    </td>
                                    <td class="pb-4 pr-4">
                                        <input type="text" name="itens[descricao][]" placeholder="Descrição do serviço..." class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                                    </td>
                                    <td class="pb-4 pr-4">
                                        <input type="number" name="itens[quantidade][]" value="1" min="1" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm text-center">
                                    </td>
                                    <td class="pb-4 pr-4">
                                        <input type="number" name="itens[valor_unitario][]" value="0.00" step="0.01" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                                    </td>
                                    <td class="pb-4 text-right">
                                        <button type="button" onclick="removeItem(this)" class="text-slate-600 hover:text-red-500 transition-colors">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Side: Totals & Summary -->
            <div class="space-y-6">
                <div class="bg-slate-800 rounded-2xl border border-slate-700 shadow-xl p-8 sticky top-8">
                    <h3 class="text-lg font-bold text-white mb-6">Resumo Financeiro</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Forma de Pagamento</label>
                            <input type="text" name="forma_pagamento" placeholder="Ex: Pix, 3x Cartão..." class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Desconto (R$)</label>
                            <input type="number" name="desconto" value="0.00" step="0.01" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none">
                        </div>

                        <div class="pt-4 border-t border-slate-700 mt-6">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Observações Internas / Adicionais</label>
                            <textarea name="observacoes" rows="4" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-blue-900/20 mt-4 active:scale-[0.98]">
                            Gerar Orçamento
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function addItem() {
    const tbody = document.querySelector('#itemsTable tbody');
    const firstRow = document.querySelector('.item-row');
    const newRow = firstRow.cloneNode(true);
    
    // Clear inputs in the new row
    newRow.querySelectorAll('input').forEach(input => {
        if (input.name.includes('quantidade')) input.value = 1;
        else if (input.name.includes('valor_unitario')) input.value = '0.00';
        else input.value = '';
    });
    
    tbody.appendChild(newRow);
}

function removeItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('tr').remove();
    } else {
        alert('O orçamento deve ter pelo menos um item.');
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
