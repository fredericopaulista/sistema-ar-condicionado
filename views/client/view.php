<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento <?= $orcamento['numero'] ?> - <?= htmlspecialchars($company['company_name'] ?? 'SÓ AR BH') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .swal2-popup { border-radius: 24px !important; background: #1e293b !important; color: #f8fafc !important; border: 1px solid #334155 !important; }
        .swal2-title { color: #ffffff !important; }
        .swal2-html-container { color: #94a3b8 !important; }
        .swal2-confirm { background-color: #2563eb !important; border-radius: 12px !important; font-weight: 700 !important; padding: 12px 24px !important; }
        .swal2-cancel { background-color: #334155 !important; border-radius: 12px !important; font-weight: 700 !important; padding: 12px 24px !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 p-4 md:p-8">
    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200">
        <!-- Header -->
        <div class="bg-slate-900 text-white p-8 md:p-12 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <?php if (!empty($company['company_logo'])): ?>
                    <img src="/<?= $company['company_logo'] ?>" class="h-16 mb-4 object-contain brightness-0 invert">
                <?php else: ?>
                    <h1 class="text-3xl font-extrabold tracking-tight mb-2"><?= htmlspecialchars($company['company_name'] ?? 'SÓ AR BH') ?></h1>
                <?php endif; ?>
                <p class="text-slate-400 text-sm">Climatização e Serviços Profissionais</p>
                <div class="mt-4 flex flex-wrap gap-4 text-xs text-slate-500">
                    <?php if (!empty($company['company_phone'])): ?>
                        <span><i class="fa-solid fa-phone mr-1"></i> <?= htmlspecialchars($company['company_phone']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($company['company_whatsapp'])): ?>
                        <span><i class="fa-brands fa-whatsapp mr-1 text-emerald-500"></i> <?= htmlspecialchars($company['company_whatsapp']) ?></span>
                    <?php endif; ?>
                    <span><i class="fa-solid fa-envelope mr-1"></i> <?= htmlspecialchars($company['mail_user'] ?? 'contato@soarbh.com.br') ?></span>
                </div>
            </div>
            <div class="mt-6 md:mt-0 text-right">
                <div class="uppercase text-xs font-bold text-blue-500 mb-1">Orçamento</div>
                <div class="text-2xl font-mono"><?= $orcamento['numero'] ?></div>
                <div class="text-slate-500 text-sm mt-1"><?= date('d/m/Y', strtotime($orcamento['data_emissao'])) ?></div>
            </div>
        </div>

        <div class="p-8 md:p-12 space-y-12">
            <!-- Client Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-12 border-b border-slate-100">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Destinatário</h3>
                    <p class="text-lg font-bold text-slate-900"><?= $orcamento['cliente_nome'] ?></p>
                    <p class="text-slate-500 text-sm mt-1">
                        <?= $orcamento['cliente_endereco'] ?><?= !empty($orcamento['cliente_numero']) ? ', ' . $orcamento['cliente_numero'] : '' ?>
                        <?= !empty($orcamento['cliente_bairro']) ? ' - ' . $orcamento['cliente_bairro'] : '' ?>
                    </p>
                    <p class="text-slate-500 text-sm">
                        <?= !empty($orcamento['cliente_cep']) ? 'CEP: ' . $orcamento['cliente_cep'] . ' - ' : '' ?>
                        <?= $orcamento['cliente_email'] ?>
                    </p>
                </div>
                <div class="md:text-right">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Informações</h3>
                    <p class="text-slate-600 text-sm">Validade: <strong class="text-slate-900"><?= $orcamento['validade_dias'] ?> dias</strong></p>
                    <p class="text-slate-600 text-sm">Pagamento: <strong class="text-slate-900"><?= $orcamento['forma_pagamento'] ?></strong></p>
                </div>
            </div>

            <!-- Items Table -->
            <div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <th class="pb-4">Descrição do Serviço</th>
                            <th class="pb-4 text-center">Qtd</th>
                            <th class="pb-4 text-right">Unitário</th>
                            <th class="pb-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($orcamento['itens'] as $item): ?>
                        <tr>
                            <td class="py-6">
                                <p class="font-bold text-slate-900"><?= $item['categoria'] ?></p>
                                <p class="text-slate-500 text-sm mt-1"><?= $item['descricao'] ?></p>
                            </td>
                            <td class="py-6 text-center text-slate-600"><?= $item['quantidade'] ?></td>
                            <td class="py-6 text-right text-slate-600">R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                            <td class="py-6 text-right font-bold text-slate-900">R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Bottom Totals -->
            <div class="flex flex-col md:flex-row justify-between pt-12">
                <div class="md:w-1/2 mb-8 md:mb-0">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Observações</h3>
                    <p class="text-slate-500 text-sm leading-relaxed whitespace-pre-line"><?= $orcamento['observacoes'] ?: 'Nenhuma observação adicional.' ?></p>
                </div>
                <div class="md:w-1/3 bg-slate-50 p-8 rounded-2xl flex flex-col space-y-3">
                    <div class="flex justify-between text-sm text-slate-500">
                        <span>Subtotal</span>
                        <span>R$ <?= number_format($orcamento['valor_total'], 2, ',', '.') ?></span>
                    </div>
                    <?php if ($orcamento['desconto'] > 0): ?>
                    <div class="flex justify-between text-sm text-emerald-600">
                        <span>Desconto</span>
                        <span>- R$ <?= number_format($orcamento['desconto'], 2, ',', '.') ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-xl font-extrabold text-slate-900 pt-3 border-t border-slate-200">
                        <span>Total</span>
                        <span>R$ <?= number_format($orcamento['valor_final'], 2, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Interaction Bar -->
        <?php if ($orcamento['status'] != 'assinado'): ?>
        <div id="action-bar" class="p-8 bg-slate-50 border-t border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-slate-600 text-sm font-medium">Você concorda com as condições acima?</p>
            <div class="flex space-x-4 w-full md:w-auto">
                <button onclick="approve()" class="flex-1 md:flex-none px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-900/10 flex items-center justify-center">
                    <i class="fa-solid fa-check mr-2"></i> Aprovar Orçamento
                </button>
                <button onclick="showChangeModal()" class="flex-1 md:flex-none px-8 py-3 bg-slate-700 hover:bg-slate-600 text-white font-bold rounded-xl transition-all flex items-center justify-center">
                    <i class="fa-solid fa-comment-dots mr-2"></i> Solicitar Alteração
                </button>
            </div>
        </div>
        <?php endif; ?>

        <!-- Modal Solicitar Alteração -->
        <div id="changeModal" class="hidden fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-slate-800 w-full max-w-lg rounded-3xl border border-slate-700 shadow-2xl p-8">
                <h3 class="text-2xl font-bold text-white mb-4">O que precisa ser alterado?</h3>
                <textarea id="changeText" rows="5" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-2xl text-white outline-none focus:ring-2 focus:ring-blue-500 mb-6" placeholder="Descreva aqui sua solicitação..."></textarea>
                <div class="flex gap-4">
                    <button onclick="hideChangeModal()" class="flex-1 px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-bold rounded-2xl transition-all">Cancelar</button>
                    <button onclick="submitChange()" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-blue-900/20">Enviar Solicitação</button>
                </div>
            </div>
        </div>

        <!-- Modal Assinatura Digital -->
        <div id="signatureModal" class="hidden fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden">
                <div class="p-6 bg-slate-900 text-white flex justify-between items-center">
                    <h3 class="text-xl font-bold">Assinatura Digital</h3>
                    <button onclick="hideSignatureModal()" class="text-slate-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div class="p-8">
                    <p class="text-slate-600 text-sm mb-6">Utilize o mouse ou o dedo (em dispositivos touch) para assinar no campo abaixo:</p>
                    
                    <div class="relative bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-4 mb-6">
                        <canvas id="signature-pad" class="w-full h-64 cursor-crosshair"></canvas>
                        <button onclick="clearSignature()" class="absolute top-4 right-4 text-xs font-bold text-slate-400 hover:text-red-500 transition-colors uppercase">
                            <i class="fa-solid fa-eraser mr-1"></i> Limpar
                        </button>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4">
                        <button onclick="hideSignatureModal()" class="flex-1 px-6 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-2xl transition-all">
                            Cancelar
                        </button>
                        <button onclick="submitApproval()" class="flex-1 px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-blue-900/20">
                            Confirmar Assinatura
                        </button>
                    </div>
                    
                    <p class="text-center text-[10px] text-slate-400 mt-6 mt-6 uppercase tracking-widest leading-relaxed">
                        Ao assinar, você concorda com os termos do orçamento e com o registro do seu IP e carimbo de data/hora para validade jurídica desta transação.
                    </p>
                </div>
            </div>
        </div>

        <div id="success-bar" class="hidden p-8 bg-emerald-500 text-white text-center font-bold">
            <i class="fa-solid fa-circle-check mr-2"></i> Orçamento assinado com sucesso!
        </div>
    </div>

    <p class="text-center text-slate-400 text-xs mt-8 uppercase font-bold tracking-widest">
        &copy; <?= date('Y') ?> <?= htmlspecialchars($company['company_name'] ?? 'SÓ AR BH') ?> - Todos os direitos reservados
    </p>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
    <script>
    let signaturePad;

    window.onload = function() {
        const canvas = document.getElementById('signature-pad');
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(15, 23, 42)'
        });

        // Handle canvas resizing
        function resizeCanvas() {
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
        window.onresize = resizeCanvas;
        resizeCanvas();
    };

    function clearSignature() {
        signaturePad.clear();
    }

    function showSignatureModal() {
        document.getElementById('signatureModal').classList.remove('hidden');
        // Small delay to ensure canvas size is correct
        setTimeout(() => {
            const canvas = document.getElementById('signature-pad');
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }, 100);
    }

    function hideSignatureModal() {
        document.getElementById('signatureModal').classList.add('hidden');
    }

    function approve() {
        showSignatureModal();
    }

    async function submitApproval() {
        if (signaturePad.isEmpty()) {
            return Swal.fire('Atenção', 'Por favor, realize a sua assinatura.', 'warning');
        }

        const signature = signaturePad.toDataURL();
        
        // Show loading
        Swal.fire({
            title: 'Processando...',
            text: 'Finalizando a assinatura do seu contrato.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const token = '<?= $orcamento['token_publico'] ?>';
            const response = await fetch(`/p/${token}/aprovar`, { 
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ signature: signature })
            });
            const resultData = await response.json();
            
            if (resultData.success) {
                hideSignatureModal();
                await Swal.fire({
                    title: 'Assinado!',
                    text: 'Orçamento aprovado e assinado com sucesso!',
                    icon: 'success',
                    confirmButtonText: 'Entendido'
                });
                document.getElementById('action-bar').classList.add('hidden');
                document.getElementById('success-bar').classList.remove('hidden');
            } else {
                Swal.fire('Erro!', resultData.message, 'error');
            }
        } catch (error) {
            Swal.fire('Erro!', 'Ocorreu um problema na conexão.', 'error');
        }
    }

    function showChangeModal() {
        document.getElementById('changeModal').classList.remove('hidden');
    }

    function hideChangeModal() {
        document.getElementById('changeModal').classList.add('hidden');
    }

    async function submitChange() {
        const text = document.getElementById('changeText').value;
        if (!text) {
            return Swal.fire('Atenção', 'Por favor, descreva a alteração desejada.', 'warning');
        }

        try {
            const token = '<?= $orcamento['token_publico'] ?>';
            const response = await fetch(`/p/${token}/solicitar-alteracao`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mensagem: text })
            });
            const data = await response.json();
            if (data.success) {
                await Swal.fire({
                    title: 'Enviado!',
                    text: 'Sua solicitação foi enviada com sucesso! Entraremos em contato em breve.',
                    icon: 'success'
                });
                window.location.reload();
            } else {
                Swal.fire('Erro', data.message, 'error');
            }
        } catch (e) {
            Swal.fire('Erro', 'Problema na conexão.', 'error');
        }
    }
    </script>
</body>
</html>
