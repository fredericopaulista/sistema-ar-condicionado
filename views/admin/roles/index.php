<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white uppercase tracking-tight">Níveis de Acesso</h1>
            <p class="text-slate-400 mt-1">Gerencie os papéis e permissões da equipe.</p>
        </div>
        <a href="/roles/create" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-900/20 flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Novo Nível
        </a>
    </div>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'admin_delete'): ?>
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm flex items-center shadow-lg">
        <i class="fa-solid fa-circle-exclamation mr-3"></i>
        O nível "Administrador" não pode ser excluído.
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($roles as $role): ?>
        <div class="bg-slate-800 rounded-3xl border border-slate-700 shadow-xl p-6 flex flex-col justify-between hover:border-slate-500 transition-all group">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-blue-500/10 text-blue-400 border border-blue-500/20">
                        <?= htmlspecialchars($role['slug']) ?>
                    </span>
                    <span class="text-xs text-slate-500 font-medium">
                        <?= $role['total_permissoes'] ?> permissões
                    </span>
                </div>
                <h3 class="text-xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors"><?= htmlspecialchars($role['nome']) ?></h3>
                <p class="text-slate-400 text-sm mb-6 line-clamp-2"><?= htmlspecialchars($role['descricao'] ?: 'Sem descrição.') ?></p>
            </div>
            
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-700/50">
                <a href="/roles/edit/<?= $role['id'] ?>" class="p-2 text-slate-400 hover:text-blue-400 transition-all">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <?php if ($role['slug'] !== 'admin'): ?>
                <button onclick="confirmDelete(<?= $role['id'] ?>)" class="p-2 text-slate-400 hover:text-red-400 transition-all">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Deseja realmente excluir este nível de acesso? Isso pode afetar usuários vinculados.')) {
        window.location.href = '/roles/delete/' + id;
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
