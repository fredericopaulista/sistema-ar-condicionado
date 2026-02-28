<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="/usuarios" class="text-blue-400 hover:text-blue-300 text-sm font-bold uppercase tracking-widest flex items-center mb-4 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Voltar para lista
        </a>
        <h1 class="text-3xl font-bold text-white uppercase tracking-tight">Novo Usuário</h1>
        <p class="text-slate-400 mt-1">Adicione um novo membro à equipe.</p>
    </div>

    <form action="/usuarios/store" method="POST" class="bg-slate-800 rounded-3xl border border-slate-700 shadow-2xl overflow-hidden p-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nome Completo</label>
                <input type="text" name="nome" required class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">E-mail (Acesso)</label>
                <input type="email" name="email" required class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Senha</label>
                <input type="password" name="senha" required class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Função / Nível de Acesso</label>
                <select name="role_id" required class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    <?php foreach ($roles as $r): ?>
                    <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-700/50">
            <button type="submit" class="w-full px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-xl shadow-blue-900/20 uppercase tracking-widest text-sm">
                Criar Usuário
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
