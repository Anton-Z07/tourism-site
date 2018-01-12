<?php

namespace App\Model\Validator;

use Luracast\Restler\RestException;

class Email extends AbstractValidator {
    
    public function invalidate($data, $options) {
        
        $data = parent::invalidate($data, $options);
        
        if(filter_var($data, FILTER_VALIDATE_EMAIL) === false) {
            throw new RestException(400, "'$data' is not email");
        }
        
        return $data;
    }
    
}

?>
