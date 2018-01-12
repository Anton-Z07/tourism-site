<?php

namespace App\Model\Validator;

use Luracast\Restler\RestException;

class Constrain extends AbstractValidator {
    protected $extended = [
        'values'=>['none']
    ];
    
    protected $values;
    
    public function invalidate($data, $options) {
        
        $data = parent::invalidate($data, $options);
        
        if(isset($this->values[$data])) {
            return $data;
        } elseif(!is_null($flip = array_flip($this->values)) && isset($flip[$data])) {
            return $flip[$data];
        } else {
            throw new RestException(400, "'$data' is invalid value!");
        }
        
        return $data;
    }
    
    protected function initOptions()
    {
        if(isset($this->options['values'])) {
            $this->values = $this->options['values'];
        } else {
            unset($this->values);
        }
    }
    
}

?>
