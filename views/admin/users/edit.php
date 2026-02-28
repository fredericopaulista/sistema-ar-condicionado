<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="/usuarios" class="text-blue-400 hover:text-blue-300 text-sm font-bold uppercase tracking-widest flex items-center mb-4 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Voltar para lista
        </a>
        <h1 class="text-3xl font-bold text-white uppercase tracking-tight">Editar Usuário</h1>
        <p class="text-slate-400 mt-1">Atualize as informações de <?= htmlspecialchars($user['nome']) ?>.</p>
    </div>

    <form action="/usuarios/update/<?= $user['id'] ?>" method="POST" class="bg-slate-800 rounded-3xl border border-slate-700 shadow-2xl overflow-hidden p-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nome Completo</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">E-mail (Acesso)</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nova Senha (deixe em branco para manter)</label>
                <input type="password" name="senha" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Função / Nível de Acesso</label>
                <select name="role_id" required class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white outline-none focus:ring-2 focus:ring-blue-500 transition-all" <?= $user['id'] == $_SESSION['user_id'] ? 'disabled' : '' ?>>
                    <?php foreach ($roles as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= $user['role_id'] == $r['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r['nome']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($user['id'] == $_SESSION['user_id']): ?>
                    <input type="hidden" name="role_id" value="<?= $user['role_id'] ?>">
                    <p class="text-[10px] text-amber-500 mt-2">Você não pode alterar seu próprio nível de acesso.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-700/50">
            <button type="submit" class="w-full px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-xl shadow-blue-900/20 uppercase tracking-widest text-sm">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
