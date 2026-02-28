<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">Configurações do Sistema</h1>
            <p class="text-slate-400 mt-1">Gerencie chaves de API, servidor de e-mail e modelos de contrato.</p>
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
                <form action="/configuracoes/update" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    <!-- Company Identity -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider flex items-center">
                                <i class="fa-solid fa-id-card mr-2"></i> Identidade da Empresa
                            </h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2 flex items-center gap-6 p-4 bg-slate-900/50 rounded-xl border border-slate-700">
                                <div class="flex-shrink-0">
                                    <?php if (!empty($config['company_logo'])): ?>
                                        <img src="/<?= $config['company_logo'] ?>" class="w-20 h-20 object-contain bg-white rounded-lg p-2" id="logoPreview">
                                    <?php else: ?>
                                        <div class="w-20 h-20 bg-slate-800 rounded-lg flex items-center justify-center text-slate-600 border-2 border-dashed border-slate-700">
                                            <i class="fa-solid fa-image text-2xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow">
                                    <label class="block text-xs font-medium text-slate-400 mb-2">Logo da Empresa</label>
                                    <input type="file" name="logo" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-600/10 file:text-blue-400 hover:file:bg-blue-600/20 transition-all cursor-pointer">
                                    <p class="text-[10px] text-slate-500 mt-2 italic">Formatos: PNG, JPG, SVG. Tamanho recomendado: 400x400px.</p>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-slate-400 mb-1">Nome da Empresa</label>
                                <input type="text" name="config[company_name]" value="<?= htmlspecialchars($config['company_name'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">CNPJ</label>
                                <input type="text" name="config[company_cnpj]" value="<?= htmlspecialchars($config['company_cnpj'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Telefone Fixo</label>
                                <input type="text" name="config[company_phone]" value="<?= htmlspecialchars($config['company_phone'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-slate-400 mb-1">WhatsApp</label>
                                <input type="text" name="config[company_whatsapp]" value="<?= htmlspecialchars($config['company_whatsapp'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-slate-400 mb-1">Endereço Completo</label>
                                <input type="text" name="config[company_address]" value="<?= htmlspecialchars($config['company_address'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 text-sm outline-none focus:ring-2 focus:ring-blue-500" placeholder="Rua, Número, Bairro, Cidade/Estado">
                            </div>
                        </div>
                    </div>

                    <!-- SMTP Settings -->
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center">
                            <i class="fa-solid fa-envelope mr-2"></i> Configurações de E-mail (SMTP)
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Servidor SMTP</label>
                                <input type="text" name="config[mail_host]" value="<?= htmlspecialchars($config['mail_host'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Porta</label>
                                <input type="text" name="config[mail_port]" value="<?= htmlspecialchars($config['mail_port'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Usuário / E-mail</label>
                                <input type="text" name="config[mail_user]" value="<?= htmlspecialchars($config['mail_user'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Senha</label>
                                <input type="password" name="config[mail_pass]" value="<?= htmlspecialchars($config['mail_pass'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Segurança</label>
                                <select name="config[mail_secure]" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="tls" <?= ($config['mail_secure'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS (Porta 587)</option>
                                    <option value="ssl" <?= ($config['mail_secure'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL (Porta 465)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Nome do Remetente (Ex: SÓ AR BH)</label>
                                <input type="text" name="config[mail_from_name]" value="<?= htmlspecialchars($config['mail_from_name'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-slate-400 mb-1">E-mail do Remetente (Obrigatório para alguns servidores)</label>
                                <input type="text" name="config[mail_from_email]" value="<?= htmlspecialchars($config['mail_from_email'] ?? '') ?>" placeholder="Se vazio, usará o Usuário / E-mail acima" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-300 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>


                    <!-- Contract Template -->
                    <div class="pt-6 border-t border-slate-700/50">
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center">
                            <i class="fa-solid fa-file-signature mr-2"></i> Modelo de Contrato (HTML)
                        </h3>
                        <textarea name="conteudo" rows="15" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-slate-300 font-mono text-sm outline-none focus:ring-2 focus:ring-blue-500 resize-none"><?= htmlspecialchars($template['conteudo']) ?></textarea>
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
                        <code class="text-emerald-400 font-bold">{{empresa_nome}}</code>
                        <p class="text-slate-500 text-xs mt-1">Nome da sua empresa</p>
                    </div>
                    <div class="p-3 bg-slate-900 rounded-xl border border-slate-700">
                        <code class="text-emerald-400 font-bold">{{empresa_cnpj}}</code>
                        <p class="text-slate-500 text-xs mt-1">CNPJ da sua empresa</p>
                    </div>
                    <div class="p-3 bg-slate-900 rounded-xl border border-slate-700">
                        <code class="text-emerald-400 font-bold">{{empresa_endereco}}</code>
                        <p class="text-slate-500 text-xs mt-1">Endereço da sua empresa</p>
                    </div>
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
