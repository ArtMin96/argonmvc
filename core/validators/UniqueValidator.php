<?php

namespace Core\Validators;
use Core\Validators\CustomValidator;
use Core\helper;

class UniqueValidator extends CustomValidator {
    
    public function runValidation() {
        $value = $this->_model->{$this->field};

        $conditions = ["{$this->field} = ?"];
        $binds = [$value];
        
        // check updating record
        if(!empty($this->_model->id)) {
            $conditions[] = "id = ?";
            $binds[] = $this->_model->id;
        }
        
        // this allows you to check multiple fields for Unique
        foreach($this->additionalFieldData as $adds) {
            $conditions[] = "{$adds} = ?";
            $binds[] = $this->_model->{$adds};
        }
        
        if($value == '' || !isset($value)){
            // this allows unique validator to be used with empty strings for fields that are not required.
            return true;
        }
        
        $queryParams = ['conditions' => $conditions, 'bind' => $binds];
        $other = $this->_model::findFirst($queryParams);
        return (!$other);
    }
    
}