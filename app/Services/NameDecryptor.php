<?php
namespace App\Services;

use App\Core\ConfigManager;
use App\Core\Logger;
use App\Models\CryptoManager;

class NameDecryptor {
    private $cryptoManager;
    private $config;
    
    public function __construct() {
        $this->cryptoManager = new CryptoManager();
        $this->config = ConfigManager::load('encryption');
    }
    
    public function decryptName($encryptedFilePath, $key) {
        $pathInfo = pathinfo($encryptedFilePath);
        $directory = $pathInfo['dirname'];
        $encryptedFilename = $pathInfo['filename'];
        
        try {
            $decodedName = base64_decode(urldecode($encryptedFilename));
            $decryptedName = $this->cryptoManager->decryptData($decodedName, $key);
            
            if ($decryptedName === false) {
                return false;
            }
            
            $originalExtension = pathinfo($decryptedName, PATHINFO_EXTENSION);
            $newFilePath = $directory . DIRECTORY_SEPARATOR . $decryptedName;
            
            Logger::debug("Filename decrypted", [
                'encrypted' => $encryptedFilename,
                'decrypted' => $decryptedName
            ]);
            
            return $newFilePath;
            
        } catch (\Exception $e) {
            Logger::error("Filename decryption failed", [
                'file' => $encryptedFilePath,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    public function decryptDirectoryName($encryptedDirPath, $key) {
        $pathInfo = pathinfo($encryptedDirPath);
        $parentDir = $pathInfo['dirname'];
        $encryptedDirName = $pathInfo['filename'];
        
        $decodedName = base64_decode(urldecode($encryptedDirName));
        $decryptedName = $this->cryptoManager->decryptData($decodedName, $key);
        
        if ($decryptedName === false) {
            return false;
        }
        
        $newDirPath = $parentDir . DIRECTORY_SEPARATOR . $decryptedName;
        return rename($encryptedDirPath, $newDirPath);
    }
}