<?php

namespace App\Services;

use GuzzleHttp\Client;

class AssinafyService
{
    private $client;
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $this->apiKey = $config['assinafy']['api_key'];
        $this->baseUrl = $config['assinafy']['base_url'];
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    public function enviarParaAssinatura($orcamento, $pdfBase64)
    {
        try {
            $response = $this->client->post('docs', [
                'json' => [
                    'name' => "Contrato SÓ AR BH - " . $orcamento['numero'],
                    'file' => $pdfBase64,
                    'signers' => [
                        [
                            'name' => $orcamento['cliente_nome'],
                            'email' => $orcamento['cliente_email'],
                            'document' => $orcamento['cliente_cpf_cnpj']
                        ]
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return [
                'success' => true,
                'document_id' => $data['id'] ?? null,
                'sign_url' => $data['sign_url'] ?? null
            ];
        } catch (\Exception $e) {
            error_log("Assinafy API Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getDocumentStatus($documentId)
    {
        try {
            $response = $this->client->get("docs/{$documentId}");
            $data = json_decode($response->getBody(), true);
            return [
                'success' => true,
                'status' => $data['status'] ?? null, // e.g. signed, pending
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
