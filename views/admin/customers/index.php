<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <div>
        <p class="text-slate-400">Total de <?= count($clientes) ?> clientes cadastrados.</p>
    </div>
    <a href="/clientes/novo" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl transition-all shadow-lg shadow-blue-900/20 font-medium">
        <i class="fa-solid fa-plus mr-2"></i> Novo Cliente
    </a>
</div>

<div class="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden shadow-xl">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-700/50">
            <tr>
                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Nome</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">E-mail</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Telefone</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">CPF/CNPJ</th>
                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-700">
            <?php foreach ($clientes as $cliente): ?>
            <tr class="hover:bg-slate-700/30 transition-colors">
                <td class="px-6 py-4 text-white font-medium"><?= $cliente['nome'] ?></td>
                <td class="px-6 py-4 text-slate-300"><?= $cliente['email'] ?></td>
                <td class="px-6 py-4 text-slate-300"><?= $cliente['telefone'] ?></td>
                <td class="px-6 py-4 text-slate-300 font-mono text-xs"><?= $cliente['cpf_cnpj'] ?></td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="/clientes/editar/<?= $cliente['id'] ?>" class="text-blue-400 hover:text-blue-300 p-2 rounded-lg hover:bg-blue-500/10 transition-all">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="/clientes/deletar/<?= $cliente['id'] ?>" onclick="return confirm('Tem certeza?')" class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/10 transition-all">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($clientes)): ?>
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                    <i class="fa-solid fa-user-slash block text-4xl mb-4 opacity-20"></i>
                    Nenhum cliente encontrado.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
