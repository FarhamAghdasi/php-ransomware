<?php
namespace App\Core;

class Router {
    private $routes = [];
    
    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }
    
    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->matchPath($route['path'], $requestPath)) {
                return $this->callHandler($route['handler']);
            }
        }
        
        http_response_code(404);
        echo "404 Not Found";
    }
    
    private function matchPath($routePath, $requestPath) {
        return $routePath === $requestPath;
    }
    
    private function callHandler($handler) {
        if (is_callable($handler)) {
            return call_user_func($handler);
        }
        
        if (is_string($handler) && strpos($handler, '@') !== false) {
            list($class, $method) = explode('@', $handler);
            $className = "App\\Controllers\\{$class}";
            
            if (class_exists($className)) {
                $controller = new $className();
                if (method_exists($controller, $method)) {
                    return $controller->$method();
                }
            }
        }
        
        throw new \Exception("Invalid handler: " . print_r($handler, true));
    }
}