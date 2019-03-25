<?php

/*
 * Type Hinting by default disabled
 */
// declare(strict_types = 1);

use Core\{Session, Cookie, Router, Mailer};
use App\Models\Users;

// Set separator
define('DS', DIRECTORY_SEPARATOR);

// Set the current working directory
define('ROOT', getcwd());

// Set the current directory correctly for CLI requests
if(defined('STDIN')) {
  chdir(dirname(__FILE__));
}

// Load configuration and helper functions
require_once(ROOT.DS.'config'.DS.'config.php');
require_once(ROOT.DS.'app'.DS.'lib'.DS.'helpers'.DS.'functions.php');

// Loading autoload.php for installed packages
require __DIR__.'/vendor/autoload.php';

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

// PHPMailer configuration

//$mailer = new PHPMailer\PHPMailer\PHPMailer();
//
//$mailer->isSMTP();
//$mailer->Host = 'smtp.gmail.com';
//$mailer->SMTPAuth = true;
//$mailer->SMTPSecure = 'tls';
//$mailer->Port = 587;
//$mailer->Username = 'artminasyanart96@gmail.com';
//$mailer->Password = 'password96min';
//$mailer->From = 'artminasyanart96@gmail.com';
//$mailer->isHTML(true);
//
//$mail = new Core\Mailer\Mailer($mailer);
//$mail->send(ROOT.'/app/views/home/test.php', ['name' => 'Artur'], function($m) {
//    $m->to('artminasyanart96@gmail.com');
//    $m->subject('Welcome to the site!');
//});

$url = isset($_SERVER['PATH_INFO']) ? explode('/', ltrim($_SERVER['PATH_INFO'], '/')) : [];

if(!Session::exists(CURRENT_USER_SESSION_NAME) && Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
    Users::loginUserFromCookie();
}

// Route the request
Router::route($url);
