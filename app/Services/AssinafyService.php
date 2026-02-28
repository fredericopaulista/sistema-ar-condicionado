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
        $configModel = new \App\Models\Configuracao();
        $config = $configModel->all();

        $this->apiKey = $config['assinafy_api_key'] ?? '';
        $this->baseUrl = $config['assinafy_base_url'] ?? '';
        
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

            $body = (string)$response->getBody();
            $data = json_decode($body, true);
            
            // Debug log
            error_log("Assinafy API Response Body: " . $body);

            // Extract sign_url correctly. Assinafy returns 'signing_urls' as an array
            $signUrl = $data['sign_url'] ?? null;
            if (!$signUrl && !empty($data['signing_urls']) && is_array($data['signing_urls'])) {
                $signUrl = $data['signing_urls'][0]['url'] ?? null;
            }

            return [
                'success' => true,
                'document_id' => $data['id'] ?? null,
                'sign_url' => $signUrl
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
