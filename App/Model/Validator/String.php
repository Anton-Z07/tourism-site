<?php

namespace App\Model\Validator;

use Luracast\Restler\RestException;

class String extends AbstractValidator {
    
    protected $extended = [];
    
    protected $min;
    protected $max;
    protected $default;
    
    public function invalidate($data, $options) {
        
        $data = parent::invalidate($data, $options);
        
        if(!is_string($data)) {
            $data = strval($data);
        }
        
        if(isset($this->default) && strlen($data) < 1) {
            $data = $this->default;
        }        
        
        if(isset($this->min) && strlen($data) < $this->min) {
            throw new RestException(400, "'$data' text lingth is less that {$this->min}");
        }
        
        if(isset($this->max) && strlen($data) > $this->max) {
            throw new RestException(400, "'$data' text lingth is more that {$this->max}");
        }
        
        return $data;
    }
    
    protected function initOptions()
    {
        if(isset($this->options['default'])) {
            $this->default = $this->options['default'];
        } else {
            unset($this->default);
        }
        
        if(isset($this->options['min'])) {
            $this->min = $this->options['min'];
        } else {
            unset($this->min);
        }
        
        if(isset($this->options['max'])) {
            $this->max = $this->options['max'];
        } else {
            unset($this->max);
        }
    }
}

?>
