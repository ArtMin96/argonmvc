<?php

namespace Core\Mailer;
use Core\Mailer\Message;

class Mailer {
    
    protected $mailer;
    
    public function __construct($mailer) {
        $this->mailer = $mailer;
    }
    
    public function send($template, $data, $callback) {
        $message = new Message($this->mailer);
        
        extract($data);
        ob_start();
        require $template;
        $template = ob_get_clean();
        if(ob_get_length() > 0) {
            ob_clean();
        }
        
        $message->body($template);
        call_user_func($callback, $message);
        
        $this->mailer->send();
    }
    
}