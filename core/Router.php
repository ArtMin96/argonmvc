<?php

namespace Core;
use Core\Session;
use App\Models\Users;

class Router {
    
    public static function route($url) {
        
        // Controller
        $controller = (isset($url[0]) && $url[0] != '') ? ucwords($url[0]).'Controller' : DEFAULT_CONTROLLER.'Controller';
        $controller_name = str_replace('Controller', '', $controller);
        array_shift($url);
        
        // Action
        $action = (isset($url[0]) && $url[0] != "") ? $url[0].'Action' : 'indexAction';
        $action_name = (isset($url[0]) && $url[0] != '') ? $url[0] : 'index';
        array_shift($url);
        
        // ACL check
        $grantAccess = self::hasAccess($controller_name, $action_name);
        if(!$grantAccess) {
            $controller = ACCESS_RESTRICTED.'Controller';
            $controller_name = ACCESS_RESTRICTED;
            $action = 'indexAction';
        }
        
        // Params
        $queryParams = $url;
        $controller = 'App\Controllers\\'.$controller;
        $dispatch = new $controller($controller_name, $action);
        
        if(method_exists($controller, $action)) {
            call_user_func_array([$dispatch, $action], $queryParams);
        } else {
            die("That method does not exist in the controller $controller_name");
        }
    }
    
    public static function redirect($location) {
        if(!headers_sent()) {
            header('Location: '.PROOT.$location);
            exit();
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="'.PROOT.$location.'";';
            echo '</script>';
            echo '<meta http-equiv="refresh" content="0;url='.$location.' />';
            echo '</noscript>';
            exit();
            echo '<noscript>';
        }
    }
    
    public static function hasAccess($controller_name, $action_name = 'index') {
        $acl_file = file_get_contents(ROOT.DS.'app'.DS.'accessControl.json');
        $acl = json_decode($acl_file, true);
        $current_user_acls = ['Guest'];
        $grantAccess = false;
        
        if(Session::exists(CURRENT_USER_SESSION_NAME)) {
            $current_user_acls[] = 'LoggedIn';
            foreach(Users::currentUser()->acls() as $a) {
                $current_user_acls[] = $a;
            }
        }
        
        foreach($current_user_acls as $level) {
            if(array_key_exists($level, $acl) && array_key_exists($controller_name, $acl[$level])) {
                if(in_array($action_name, $acl[$level][$controller_name]) || in_array('*', $acl[$level][$controller_name])) {
                    $grantAccess = true;
                    break;
                }
            }
        }
        
        // Check for denied
        foreach($current_user_acls as $level) {
            $denied = $acl[$level]['denied'];
            if(!empty($denied) && array_key_exists($controller_name, $denied) && in_array($action_name, $denied[$controller_name])) {
                $grantAccess = false;
                break;
            }
        }
        
        return $grantAccess;
        
    }
    
    public static function getMenu($menu) {
        $menuAry = [];
        $menuFile = file_get_contents(ROOT.DS.'app'.DS.$menu.'.json');
        $acl = json_decode($menuFile, true);
        foreach($acl as $key => $val) {
            if(is_array($val)) {
                $sub = [];
                foreach($val as $k => $v) {
                    if(substr($k,0,9) == 'separator' && !empty($sub)) {
                        $sub[$k] = '';
                        continue;
                    } elseif($finalVal = self::get_link($v)) {
                        $sub[$k] = $finalVal;
                    }
                }
                if(!empty($sub)) {
                    $menuAry[$key] = $sub;
                }
            } else {
                if($finalVal = self::get_link($val)) {
                    $menuAry[$key] = $finalVal;
                }
            }
        }
        return $menuAry;
    }
    
    public static function get_link($val) {
        // Check if external link
        if(preg_match('/https?:\/\//', $val) == 1) {
            return $val;
        } else {
            $uAry = explode('/', $val);
            $controller_name = ucwords($uAry[0]);
            $action_name = (isset($uAry[1])) ? $uAry[1] : '';
            if(self::hasAccess($controller_name, $action_name)) {
                return PROOT.$val;
            }
            return false;
        }
    }
    
    public function back() {
        if(Input::get($_POST) || Input::get($_GET)) {
            return $_SERVER['REQUEST_URI'];
        }
    }
    
    public static function goBack() {
        $previous = 'javascript:history.go(-1)';
        if(isset($_SERVER['HTTP_REFERER'])) {
            $previous = $_SERVER['HTTP_REFERER'];
        }
        return $previous;
    }
    
}