<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-slate-800 rounded-2xl border border-slate-700 shadow-xl overflow-hidden">
        <form action="/clientes/novo" method="POST" class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nome Completo / Razão Social</label>
                    <input type="text" name="nome" required class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">E-mail</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Telefone</label>
                    <input type="text" name="telefone" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">CPF / CNPJ</label>
                    <input type="text" name="cpf_cnpj" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">CEP</label>
                    <input type="text" name="cep" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="col-span-1 md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Endereço</label>
                            <input type="text" name="endereco" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Número</label>
                            <input type="text" name="numero" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Comp. <span class="text-slate-500 font-normal">(Opcional)</span></label>
                            <input type="text" name="complemento" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Bairro</label>
                            <input type="text" name="bairro" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Cidade</label>
                    <input type="text" name="cidade" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Estado (UF)</label>
                    <input type="text" name="estado" maxlength="2" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500 uppercase">
                </div>
            </div>

            <div class="flex justify-end pt-4 space-x-4">
                <a href="/clientes" class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-xl transition-all">Cancelar</a>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-900/20">
                    Salvar Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
