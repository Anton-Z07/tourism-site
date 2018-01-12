<?php

namespace App\Model\Validator;

class AbstractValidator implements ValidatorInterface {
    
    protected $extended = [];
    protected $options;
    
    public function invalidate($data, $options) {
        $this->options = array_merge($this->extended, $options['options'] ?: []);                
        $this->initOptions();
        return $data;
    }
    
    protected function initOptions()
    {
        
    }
}

?>
