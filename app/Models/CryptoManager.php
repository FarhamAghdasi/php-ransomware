<?php
namespace App\Models;

use App\Core\ConfigManager;
use App\Core\Logger;

class CryptoManager {
    private $config;
    private $salt;
    private $iv;
    
    public function __construct() {
        $this->config = ConfigManager::load('encryption');
        $this->initializeCrypto();
    }
    
    private function initializeCrypto() {
        $this->salt = random_bytes(16);
        $this->iv = random_bytes(openssl_cipher_iv_length($this->config['crypto']['cipher']));
    }
    
    public function generateKey() {
        $key = random_bytes($this->config['crypto']['key_length']);
        Logger::info("Generated new encryption key");
        return $key;
    }
    
    public function deriveKey($key, $salt = null) {
        $salt = $salt ?: $this->salt;
        return openssl_pbkdf2(
            $key,
            $salt,
            $this->config['crypto']['key_length'],
            $this->config['crypto']['iterations'],
            $this->config['crypto']['algorithm']
        );
    }
    
    public function encryptData($data, $key) {
        $derivedKey = $this->deriveKey($key);
        $encrypted = openssl_encrypt(
            $data,
            $this->config['crypto']['cipher'],
            $derivedKey,
            OPENSSL_RAW_DATA,
            $this->iv
        );
        
        return $encrypted !== false ? $encrypted : false;
    }
    
    public function decryptData($encryptedData, $key) {
        $derivedKey = $this->deriveKey($key);
        $decrypted = openssl_decrypt(
            $encryptedData,
            $this->config['crypto']['cipher'],
            $derivedKey,
            OPENSSL_RAW_DATA,
            $this->iv
        );
        
        return $decrypted !== false ? $decrypted : false;
    }
    
    public function getCryptoInfo() {
        return [
            'salt' => base64_encode($this->salt),
            'iv' => base64_encode($this->iv),
            'config' => $this->config
        ];
    }
    
    public function setCryptoParams($salt, $iv) {
        $this->salt = base64_decode($salt);
        $this->iv = base64_decode($iv);
    }
}