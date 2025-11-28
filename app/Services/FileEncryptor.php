<?php
namespace App\Services;

use App\Core\ConfigManager;
use App\Core\Logger;
use App\Models\CryptoManager;

class FileEncryptor {
    private $cryptoManager;
    private $config;
    
    public function __construct() {
        $this->cryptoManager = new CryptoManager();
        $this->config = ConfigManager::load('encryption');
    }
    
    public function encrypt($filePath, $key) {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }
        
        $fileContent = file_get_contents($filePath);
        if ($fileContent === false) {
            throw new \Exception("Cannot read file: {$filePath}");
        }
        
        $encryptedContent = $this->cryptoManager->encryptData($fileContent, $key);
        if ($encryptedContent === false) {
            throw new \Exception("Encryption failed for file: {$filePath}");
        }
        
        $encryptedFilePath = $filePath . '.' . $this->config['crypto']['extension'];
        
        if (file_put_contents($encryptedFilePath, $encryptedContent, LOCK_EX) === false) {
            throw new \Exception("Cannot write encrypted file: {$encryptedFilePath}");
        }
        
        // Remove original file
        unlink($filePath);
        
        // Encrypt filename
        $nameEncryptor = new NameEncryptor();
        $nameEncryptor->encryptName($encryptedFilePath, $key);
        
        Logger::info("File encrypted successfully", ['file' => $filePath]);
        return true;
    }
    
    public function decrypt($encryptedFilePath, $key) {
        if (!file_exists($encryptedFilePath)) {
            throw new \Exception("Encrypted file not found: {$encryptedFilePath}");
        }
        
        // Decrypt filename first
        $nameEncryptor = new NameDecryptor();
        $originalFilePath = $nameEncryptor->decryptName($encryptedFilePath, $key);
        
        if (!$originalFilePath) {
            throw new \Exception("Cannot decrypt filename: {$encryptedFilePath}");
        }
        
        $encryptedContent = file_get_contents($encryptedFilePath);
        if ($encryptedContent === false) {
            throw new \Exception("Cannot read encrypted file: {$encryptedFilePath}");
        }
        
        $decryptedContent = $this->cryptoManager->decryptData($encryptedContent, $key);
        if ($decryptedContent === false) {
            throw new \Exception("Decryption failed for file: {$encryptedFilePath}");
        }
        
        if (file_put_contents($originalFilePath, $decryptedContent, LOCK_EX) === false) {
            throw new \Exception("Cannot write decrypted file: {$originalFilePath}");
        }
        
        // Remove encrypted file
        unlink($encryptedFilePath);
        
        Logger::info("File decrypted successfully", ['file' => $originalFilePath]);
        return true;
    }
}