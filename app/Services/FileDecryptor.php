<?php
namespace App\Services;

use App\Core\ConfigManager;
use App\Core\Logger;
use App\Models\CryptoManager;

class FileDecryptor {
    private $cryptoManager;
    private $config;
    
    public function __construct() {
        $this->cryptoManager = new CryptoManager();
        $this->config = ConfigManager::load('encryption');
    }
    
    public function decryptFile($encryptedFilePath, $key) {
        return $this->decrypt($encryptedFilePath, $key);
    }
    
    public function batchDecrypt($encryptedFiles, $key) {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];
        
        foreach ($encryptedFiles as $file) {
            try {
                if ($this->decrypt($file, $key)) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = "Failed to decrypt: {$file}";
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = "Error decrypting {$file}: " . $e->getMessage();
            }
        }
        
        return $results;
    }
    
    private function decrypt($encryptedFilePath, $key) {
        if (!file_exists($encryptedFilePath)) {
            throw new \Exception("Encrypted file not found: {$encryptedFilePath}");
        }
        
        $nameDecryptor = new NameDecryptor();
        $originalFilePath = $nameDecryptor->decryptName($encryptedFilePath, $key);
        
        if (!$originalFilePath) {
            throw new \Exception("Cannot decrypt filename: {$encryptedFilePath}");
        }
        
        $encryptedContent = file_get_contents($encryptedFilePath);
        $decryptedContent = $this->cryptoManager->decryptData($encryptedContent, $key);
        
        if ($decryptedContent === false) {
            throw new \Exception("Decryption failed for file: {$encryptedFilePath}");
        }
        
        if (file_put_contents($originalFilePath, $decryptedContent, LOCK_EX) === false) {
            throw new \Exception("Cannot write decrypted file: {$originalFilePath}");
        }
        
        unlink($encryptedFilePath);
        Logger::info("File decrypted", ['file' => $originalFilePath]);
        
        return true;
    }
}