<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white uppercase tracking-tight">Usuários</h1>
            <p class="text-slate-400 mt-1">Gerencie a equipe e níveis de acesso ao sistema.</p>
        </div>
        <a href="/usuarios/create" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-900/20 flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Novo Usuário
        </a>
    </div>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'self_delete'): ?>
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm flex items-center">
        <i class="fa-solid fa-circle-exclamation mr-3"></i>
        Você não pode excluir seu próprio usuário.
    </div>
    <?php endif; ?>

    <div class="bg-slate-800 rounded-3xl border border-slate-700 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-900/50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">E-mail</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Função / Role</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    <?php foreach ($users as $u): ?>
                    <tr class="hover:bg-slate-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center mr-3 text-blue-400 font-bold">
                                    <?= strtoupper(substr($u['nome'], 0, 1)) ?>
                                </div>
                                <span class="text-sm font-medium text-white"><?= htmlspecialchars($u['nome']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-400">
                            <?= htmlspecialchars($u['email']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                                <?= $u['role_slug'] === 'admin' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'bg-slate-900 text-slate-400 border border-slate-700' ?>">
                                <?= htmlspecialchars($u['role_nome'] ?: 'Sem Função') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="/usuarios/edit/<?= $u['id'] ?>" class="text-slate-400 hover:text-blue-400 transition-colors">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <button onclick="confirmDelete(<?= $u['id'] ?>)" class="text-slate-400 hover:text-red-400 transition-colors">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir este usuário?')) {
        window.location.href = '/usuarios/delete/' + id;
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
