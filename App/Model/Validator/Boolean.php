<?php

namespace App\Model\Validator;

class Boolean extends AbstractValidator {
    
    public function invalidate($data, $options) {
        
        $data = parent::invalidate($data, $options);
        
        if(is_bool($data)) {
            return $data;
        }
        
        if(is_string($data)) {
            if(in_array($data, ['true', true, 1])) {
                return 1;
            }
            
            if(in_array($data, ['false', false, 0])) {
                return 0;
            }
        } else {
            return (bool)$data ? 1 : 0;
        }
    }
}
