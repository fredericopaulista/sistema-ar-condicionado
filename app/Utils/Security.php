<?php

namespace App\Utils;

class Security
{
    private static $cipher = "AES-256-CBC";

    /**
     * Encrypt a string using the app key from config.
     */
    public static function encrypt($plaintext)
    {
        if (empty($plaintext)) return $plaintext;

        $key = self::getAppKey();
        $ivlen = openssl_cipher_iv_length(self::$cipher);
        $iv = openssl_random_bytes($ivlen);
        
        $ciphertext = openssl_encrypt($plaintext, self::$cipher, $key, $options = 0, $iv);
        
        // Return IV + Ciphertext for later decryption
        return base64_encode($iv . $ciphertext);
    }

    /**
     * Decrypt a string using the app key from config.
     */
    public static function decrypt($ciphertext)
    {
        if (empty($ciphertext)) return $ciphertext;

        $key = self::getAppKey();
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length(self::$cipher);
        
        $iv = substr($c, 0, $ivlen);
        $ciphertext_raw = substr($c, $ivlen);
        
        $plaintext = openssl_decrypt($ciphertext_raw, self::$cipher, $key, $options = 0, $iv);
        return $plaintext;
    }

    private static function getAppKey()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $key = $config['app_key'] ?? 'fallback_key_not_recommended';
        return substr(hash('sha256', $key), 0, 32);
    }

    /**
     * Redact a sensitive string for display.
     */
    public static function redact($value)
    {
        if (empty($value)) return '';
        return '********';
    }
}
