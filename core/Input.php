<?php

namespace Core;
use Core\{CSRF, FormHelper, Router};

class Input {
    
    public function isPost() {
        return $this->getRequestMethod() === 'POST';
    }
    
    public function isGet() {
        return $this->getRequestMethod() === 'GET';
    }
    
    public function isPut() {
        return $this->getRequestMethod() === 'PUT';
    }
    
    public function getRequestMethod() {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function get($input = false) {
        if(!$input) {
            // return entire request array and sanitize it
            $data = [];
            foreach($_REQUEST as $field => $value) {
                $data[$field] = trim(FormHelper::sanitize($value));
            }
            return $data;
        }
        return trim(FormHelper::sanitize($_REQUEST[$input]));
    }
    
    public function csrfCheck() {
        if(!CSRF::checkToken($this->get('csrf_token'))) {
            Router::redirect('restricted/badToken');
        }
        return true;
    }
        
}