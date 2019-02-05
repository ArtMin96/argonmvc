<?php

/*
 * Type Hinting by default disabled
 */
// declare(strict_types = 1);

use Core\Session;
use Core\Cookie;
use Core\Router;
use App\Models\Users;

define('DS', '/');
define('ROOT', dirname(__FILE__));

// Load configuration and helper functions
require_once(ROOT.DS.'config'.DS.'config.php');
require_once(ROOT.DS.'app'.DS.'lib'.DS.'helpers'.DS.'functions.php');

// Autoload classes

function autoload($className) {
    $classArry = explode('\\', $className);
    $class = array_pop($classArry);
    $subPath = strtolower(implode(DS, $classArry));
    $path = ROOT.DS.$subPath.DS.$class.'.php';
    if(file_exists($path)) {
        require_once($path);
    }
}

spl_autoload_register('autoload');

session_start();

$url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'], '/')) : [];

if(!Session::exists(CURRENT_USER_SESSION_NAME) && Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
    Users::loginUserFromCookie();
}

// Route the request
Router::route($url);