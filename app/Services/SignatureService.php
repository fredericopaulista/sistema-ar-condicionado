<?php

namespace App\Services;

class SignatureService
{
    /**
     * Saves a base64 signature image to disk.
     */
    public function saveSignature($orcamentoId, $base64Image)
    {
        if (empty($base64Image)) return null;

        // Remove data:image/png;base64, prefix
        $data = explode(',', $base64Image);
        if (count($data) < 2) return null;
        
        $imageBinary = base64_decode($data[1]);
        $filename = 'sig_' . $orcamentoId . '_' . time() . '.png';
        $directory = dirname(__DIR__, 2) . '/storage/assinaturas/';
        
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
        
        $path = $directory . $filename;
        file_put_contents($path, $imageBinary);
        
        return 'storage/assinaturas/' . $filename;
    }

    /**
     * Generates a unique signature hash for audit purposes.
     */
    public function generateSignatureHash($orcamento, $ip, $userAgent)
    {
        $data = [
            'id' => $orcamento['id'],
            'numero' => $orcamento['numero'],
            'cliente' => $orcamento['cliente_nome'],
            'valor' => $orcamento['valor_final'],
            'ip' => $ip,
            'ua' => $userAgent,
            'ts' => time()
        ];
        
        return hash('sha256', json_encode($data));
    }
}
