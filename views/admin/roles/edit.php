<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="/roles" class="text-blue-400 hover:text-blue-300 text-sm font-bold uppercase tracking-widest flex items-center mb-4 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Voltar
        </a>
        <h1 class="text-3xl font-bold text-white uppercase tracking-tight">Editar Nível de Acesso</h1>
        <p class="text-slate-400 mt-1">Ajuste as permissões para <?= htmlspecialchars($role['nome']) ?>.</p>
    </div>

    <form action="/roles/update/<?= $role['id'] ?>" method="POST" class="space-y-8">
        <div class="bg-slate-800 rounded-3xl border border-slate-700 shadow-2xl p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nome do Nível</label>
                    <input type="text" name="nome" value="<?= htmlspecialchars($role['nome']) ?>" required class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">SLUG (Identificador único)</label>
                    <input type="text" name="slug" value="<?= htmlspecialchars($role['slug']) ?>" required class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Descrição</label>
                    <textarea name="descricao" rows="2" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all"><?= htmlspecialchars($role['descricao']) ?></textarea>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-3xl border border-slate-700 shadow-2xl p-8">
            <h3 class="text-lg font-bold text-white flex items-center mb-6">
                <i class="fa-solid fa-lock mr-3 text-blue-500"></i> Permissões Ativas
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($permissions as $perm): ?>
                <label class="flex items-center p-4 bg-slate-900 rounded-2xl border border-slate-700 hover:border-blue-500/50 cursor-pointer transition-all group">
                    <div class="relative flex items-center">
                        <input type="checkbox" name="permissions[]" value="<?= $perm['id'] ?>" 
                            <?= in_array($perm['id'], $role['permissions']) ? 'checked' : '' ?>
                            class="w-5 h-5 rounded border-slate-700 bg-slate-800 text-blue-600 focus:ring-blue-500">
                    </div>
                    <div class="ml-4">
                        <span class="block text-sm font-bold text-white group-hover:text-blue-400 transition-colors"><?= htmlspecialchars($perm['nome']) ?></span>
                        <span class="block text-[10px] text-slate-500 uppercase tracking-widest mt-0.5"><?= htmlspecialchars($perm['slug']) ?></span>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex pt-4">
            <button type="submit" class="w-full lg:w-auto px-12 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-xl shadow-blue-900/20 uppercase tracking-widest text-sm">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
