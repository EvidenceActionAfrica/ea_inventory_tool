<?php
namespace App\Core;

class Router {
    protected $routes = [];

    public function addRoutes($routes) {
        $this->routes = $routes;
    }

    public function dispatch($url) {
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Ensure session starts
        }
    
        if (array_key_exists($url, $this->routes)) {
            list($controller, $method) = $this->routes[$url];
            $controllerInstance = new $controller();
    
            // Check if the method requires parameters
            $reflection = new \ReflectionMethod($controllerInstance, $method);
            $params = $reflection->getParameters();
    
            if (count($params) > 0) {
                $user_id = $_SESSION['user_id'] ?? null;
                if (!$user_id) {
                    header("Location: login.php"); // Redirect instead of die()
                    exit();
                }
                $controllerInstance->$method($user_id);
            } else {
                $controllerInstance->$method();
            }
        } else {
            echo "Oops!! Not Found";
        }
    }
    
}

