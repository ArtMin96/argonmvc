<?php

namespace Core\Validators;
use Core\Validators\CustomValidator;

class EmailValidator extends CustomValidator {
    
    public function runValidation() {
        $value = $this->_model->{$this->field};
        $pass = true;
        if(!empty($value)) {
            $pass = filter_var($value, FILTER_VALIDATE_EMAIL);
        }
        return $pass;
    }
    
}