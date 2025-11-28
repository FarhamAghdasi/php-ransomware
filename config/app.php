<?php
return [
    'name' => 'PHP Ransomware',
    'version' => '2.0.0',
    'environment' => 'production',
    'debug' => false,
    'url' => 'http://localhost',
    
    'timezone' => 'UTC',
    'locale' => 'en',
    
    'providers' => [
        App\Core\ConfigManager::class,
        App\Core\Logger::class,
        App\Services\FileEncryptor::class,
        App\Services\FileDecryptor::class,
        App\Services\NameEncryptor::class,
        App\Services\NameDecryptor::class,
    ]
];