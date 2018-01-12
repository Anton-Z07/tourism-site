<?php

namespace App\Model\Validator;

use Luracast\Restler\RestException;

class Int extends AbstractValidator {
    protected $extended = [
        'min' => 0,
        'max' => 4294967295,
        'default' => 0
    ];
    
    protected $min;
    protected $max;
    protected $default;
    
    public function invalidate($data, $options) {
        
        $data = parent::invalidate($data, $options);
        if(!is_int($data)) {
            $data = intval($data);
            if(isset($this->default) && $data == 0) {
                $data = $this->default;
            }
        }
        
        if(isset($this->min) && $data < $this->min) {
            throw new RestException(400, "'$data' is less that {$this->min}");
        }
        
        if(isset($this->max) && $data > $this->max) {
            throw new RestException(400, "'$data' is more that {$this->max}");
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
