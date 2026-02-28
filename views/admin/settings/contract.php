<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">Configurações de Contrato</h1>
            <p class="text-slate-400 mt-1">Edite o modelo de contrato gerado para os clientes.</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
    <div class="bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 px-6 py-4 rounded-2xl mb-8 flex items-center">
        <i class="fa-solid fa-circle-check mr-3 text-xl"></i>
        <span>Template atualizado com sucesso!</span>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Editor -->
        <div class="lg:col-span-2">
            <div class="bg-slate-800 rounded-2xl border border-slate-700 shadow-xl overflow-hidden">
                <form action="/configuracoes/update" method="POST" class="p-8">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-300 mb-2">HTML do Template</label>
                        <textarea name="conteudo" rows="25" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-slate-300 font-mono text-sm outline-none focus:ring-2 focus:ring-blue-500 resize-none"><?= htmlspecialchars($template['conteudo']) ?></textarea>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="/configuracoes/test-email" class="text-slate-400 hover:text-white text-sm flex items-center transition-colors">
                            <i class="fa-solid fa-envelope-circle-check mr-2"></i> Testar SMTP
                        </a>
                        <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-900/20">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>

            <?php if (isset($_GET['test_success'])): ?>
            <div class="bg-blue-500/10 border border-blue-500/50 text-blue-400 px-6 py-4 rounded-2xl mt-6 flex items-center">
                <i class="fa-solid fa-paper-plane mr-3"></i>
                <span>E-mail de teste enviado para seu endereço SMTP! Verifique sua caixa de entrada.</span>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['test_error'])): ?>
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-6 py-4 rounded-2xl mt-6 flex items-center">
                <i class="fa-solid fa-circle-exclamation mr-3"></i>
                <span>Falha no envio do teste. Verifique as credenciais no arquivo de configuração.</span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Help/Variables -->
        <div class="space-y-6">
            <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-lg">
                <h3 class="text-lg font-bold text-white mb-4">Variáveis Disponíveis</h3>
                <p class="text-slate-400 text-sm mb-4">Use estas tags para inserir dados dinâmicos no contrato:</p>
                <div class="space-y-3">
                    <div class="p-3 bg-slate-900 rounded-xl border border-slate-700">
                        <code class="text-blue-400 font-bold">{{cliente_nome}}</code>
                        <p class="text-slate-500 text-xs mt-1">Nome ou Razão Social</p>
                    </div>
                    <div class="p-3 bg-slate-900 rounded-xl border border-slate-700">
                        <code class="text-blue-400 font-bold">{{cliente_cpf_cnpj}}</code>
                        <p class="text-slate-500 text-xs mt-1">Documento do Cliente</p>
                    </div>
                    <div class="p-3 bg-slate-900 rounded-xl border border-slate-700">
                        <code class="text-blue-400 font-bold">{{cliente_endereco}}</code>
                        <p class="text-slate-500 text-xs mt-1">Endereço Completo</p>
                    </div>
                    <div class="p-3 bg-slate-900 rounded-xl border border-slate-700">
                        <code class="text-blue-400 font-bold">{{descricao_servicos}}</code>
                        <p class="text-slate-500 text-xs mt-1">Lista de itens do orçamento</p>
                    </div>
                    <div class="p-3 bg-slate-900 rounded-xl border border-slate-700">
                        <code class="text-blue-400 font-bold">{{valor_total}}</code>
                        <p class="text-slate-500 text-xs mt-1">Valor final do orçamento</p>
                    </div>
                    <div class="p-3 bg-slate-900 rounded-xl border border-slate-700">
                        <code class="text-blue-400 font-bold">{{forma_pagamento}}</code>
                        <p class="text-slate-500 text-xs mt-1">Condições de pagamento</p>
                    </div>
                    <div class="p-3 bg-slate-900 rounded-xl border border-slate-700">
                        <code class="text-blue-400 font-bold">{{data_atual}}</code>
                        <p class="text-slate-500 text-xs mt-1">Data de geração</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
