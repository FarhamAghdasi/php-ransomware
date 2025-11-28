<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\EncryptionController;
use App\Core\Logger;

try {
    Logger::initialize();
    
    $controller = new EncryptionController();
    $result = $controller->index();
    
    if (php_sapi_name() === 'cli') {
        if ($result['success']) {
            echo "Encryption completed successfully.\n";
            echo "Encrypted files: " . ($result['encrypted_files'] ?? 0) . "\n";
            echo "Total directories processed: " . ($result['directories'] ?? 0) . "\n";
        } else {
            echo "Encryption failed: " . ($result['error'] ?? 'Unknown error') . "\n";
            exit(1);
        }
    }
    
} catch (Exception $e) {
    if (php_sapi_name() === 'cli') {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    } else {
        http_response_code(500);
        echo "Encryption process failed. Please check the logs for details.";
    }
}