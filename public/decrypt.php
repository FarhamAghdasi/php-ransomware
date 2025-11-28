<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\DecryptionController;
use App\Core\Logger;

try {
    Logger::initialize();
    
    $controller = new DecryptionController();
    $controller->index();
    
} catch (Exception $e) {
    http_response_code(500);
    echo "Decryption process failed: " . $e->getMessage();
}