<?php
namespace App\Core;

class Logger {
    private static $logFile;
    
    public static function initialize() {
        $logDir = __DIR__ . '/../../storage/logs/';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        self::$logFile = $logDir . 'ransomware-' . date('Y-m-d') . '.log';
    }
    
    public static function log($level, $message, $context = []) {
        if (!self::$logFile) {
            self::initialize();
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context) : '';
        $logMessage = "[{$timestamp}] [{$level}] {$message} {$contextStr}" . PHP_EOL;
        
        file_put_contents(self::$logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
    
    public static function info($message, $context = []) {
        self::log('INFO', $message, $context);
    }
    
    public static function error($message, $context = []) {
        self::log('ERROR', $message, $context);
    }
    
    public static function warning($message, $context = []) {
        self::log('WARNING', $message, $context);
    }
    
    public static function debug($message, $context = []) {
        if (ConfigManager::get('app.debug')) {
            self::log('DEBUG', $message, $context);
        }
    }
}