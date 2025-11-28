<?php
namespace App\Models;

use App\Core\Logger;

class KeyStorage {
    private $keyFile;
    
    public function __construct() {
        $keyDir = __DIR__ . '/../../storage/keys/';
        if (!is_dir($keyDir)) {
            mkdir($keyDir, 0700, true);
        }
        $this->keyFile = $keyDir . 'key_info.dat';
    }
    
    public function saveKeyInfo($key, $cryptoInfo) {
        $keyInfo = [
            'key_hash' => hash('sha256', $key),
            'key' => base64_encode($key),
            'salt' => $cryptoInfo['salt'],
            'iv' => $cryptoInfo['iv'],
            'timestamp' => time(),
            'config' => $cryptoInfo['config']
        ];
        
        $encryptedData = $this->encryptKeyInfo($keyInfo, $key);
        file_put_contents($this->keyFile, $encryptedData, LOCK_EX);
        
        Logger::info("Key information saved");
    }
    
    public function loadKeyInfo($decryptionKey) {
        if (!file_exists($this->keyFile)) {
            return null;
        }
        
        $encryptedData = file_get_contents($this->keyFile);
        $keyInfo = $this->decryptKeyInfo($encryptedData, $decryptionKey);
        
        if ($keyInfo && hash('sha256', $decryptionKey) === $keyInfo['key_hash']) {
            $keyInfo['key'] = base64_decode($keyInfo['key']);
            return $keyInfo;
        }
        
        return null;
    }
    
    public function deleteKeyInfo() {
        if (file_exists($this->keyFile)) {
            unlink($this->keyFile);
            Logger::info("Key information deleted");
        }
    }
    
    private function encryptKeyInfo($data, $key) {
        $jsonData = json_encode($data);
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($jsonData, 'AES-256-CBC', hash('sha256', $key), 0, $iv);
        return base64_encode($iv . $encrypted);
    }
    
    private function decryptKeyInfo($encryptedData, $key) {
        $data = base64_decode($encryptedData);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', hash('sha256', $key), 0, $iv);
        return json_decode($decrypted, true);
    }
}