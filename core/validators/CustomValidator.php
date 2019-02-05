<?php

namespace Core\Validators;
use \Exception;

abstract class CustomValidator {
    
    public $success = true, $message = '', $field, $rule;
    protected $_model;
    
    public function __construct($model, $params) {
        $this->_model = $model;
        
        // Make sure the field exists
        if(!array_key_exists('field', $params)) {
            throw new Exception('You must include "field" element in the params array.');
        } else {
            $this->field = (is_array($params['field'])) ? $params['field'][0] : $params['field'];
        }
        
        // Make sure field exists in model
        if(!property_exists($model, $this->field)) {
            throw new Exception('The "field" does not belong to the model.');
        }
        
        // Make sure the message exists in the params array
        if(!array_key_exists('message', $params)) {
            throw new Exception('You must include "message" element in the params array.');
        } else {
            $this->message = $params['message'];
        }
        
        if(array_key_exists('rule', $params)) {
            $this->rule = $params['rule'];
        }
        
        try {
            $this->success = $this->runValidation();
        } catch (Exception $ex) {
            echo 'Validation exception on '. get_class().':'.$ex->getMessage();
        }
    }
    
    abstract public function runValidation();
    
}

