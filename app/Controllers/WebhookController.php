<?php

namespace App\Controllers;

use App\Models\Orcamento;

class WebhookController extends BaseController
{
    private $orcamentoModel;

    public function __construct()
    {
        $this->orcamentoModel = new Orcamento();
    }

    public function assinafy()
    {
        // Simple webhook implementation
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (isset($data['document_id']) && isset($data['event'])) {
            $docId = $data['document_id'];
            $event = $data['event']; // Example: 'signed'

            // Find quote by assinafy_doc_id
            $stmt = $this->orcamentoModel->getConnection()->prepare("SELECT id FROM orcamentos WHERE assinafy_doc_id = ?");
            $stmt->execute([$docId]);
            $orc = $stmt->fetch();

            if ($orc) {
                if ($event === 'signed') {
                    $this->orcamentoModel->updateStatus($orc['id'], 'assinado');
                    $this->json(['success' => true]);
                }
            }
        }

        $this->json(['success' => false], 400);
    }
}
