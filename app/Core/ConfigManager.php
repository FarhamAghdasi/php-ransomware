<?php
namespace App\Core;

class ConfigManager {
    private static $configs = [];
    
    public static function load($configName) {
        if (!isset(self::$configs[$configName])) {
            $configFile = __DIR__ . '/../../config/' . $configName . '.php';
            
            if (!file_exists($configFile)) {
                throw new \Exception("Config file not found: {$configName}");
            }
            
            self::$configs[$configName] = require $configFile;
        }
        
        return self::$configs[$configName];
    }
    
    public static function get($key, $default = null) {
        $keys = explode('.', $key);
        $configName = array_shift($keys);
        
        try {
            $config = self::load($configName);
        } catch (\Exception $e) {
            return $default;
        }
        
        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                return $default;
            }
            $config = $config[$k];
        }
        
        return $config;
    }
}