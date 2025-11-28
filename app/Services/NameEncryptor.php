<?php
namespace App\Services;

use App\Core\ConfigManager;
use App\Core\Logger;
use App\Models\CryptoManager;

class NameEncryptor {
    private $cryptoManager;
    private $config;
    
    public function __construct() {
        $this->cryptoManager = new CryptoManager();
        $this->config = ConfigManager::load('encryption');
    }
    
    public function encryptName($filePath, $key) {
        $pathInfo = pathinfo($filePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        
        $encryptedName = $this->cryptoManager->encryptData($filename, $key);
        if ($encryptedName === false) {
            throw new \Exception("Filename encryption failed: {$filename}");
        }
        
        $newFilename = urlencode(base64_encode($encryptedName));
        $newFilePath = $directory . DIRECTORY_SEPARATOR . $newFilename . '.' . $this->config['crypto']['extension'];
        
        if (rename($filePath, $newFilePath)) {
            Logger::debug("Filename encrypted", [
                'original' => $filename,
                'encrypted' => $newFilename
            ]);
            return true;
        }
        
        return false;
    }
    
    public function encryptDirectoryName($directoryPath, $key) {
        $pathInfo = pathinfo($directoryPath);
        $parentDir = $pathInfo['dirname'];
        $dirName = $pathInfo['basename'];
        
        $encryptedName = $this->cryptoManager->encryptData($dirName, $key);
        if ($encryptedName === false) {
            return false;
        }
        
        $newDirName = urlencode(base64_encode($encryptedName)) . '.' . $this->config['crypto']['extension'];
        $newDirPath = $parentDir . DIRECTORY_SEPARATOR . $newDirName;
        
        return rename($directoryPath, $newDirPath);
    }
}