<?php

// Load Composer autoloader and config
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/config.php';

use App\Core\Router;

// Load routes
$routes = require_once __DIR__ . '/../app/config/routes.php';

// Initialize the router
$router = new Router();
$router->addRoutes($routes);

// Get the current URL path, ignoring query strings
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Get the base path for the app
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';

// Get the relative path (trim base path from URL)
$relativeUrl = substr($url, strlen($basePath));

// Remove any trailing slashes
$relativeUrl = rtrim($relativeUrl, '/');

// Dispatch the route
$router->dispatch($relativeUrl);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload classes from models
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../app/models/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        die("The file $file does not exist.");
    }
});


