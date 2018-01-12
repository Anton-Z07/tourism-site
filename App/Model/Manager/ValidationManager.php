<?php

namespace App\Model\Manager;

final class ValidationManager implements ManagerInterface{
    
    /**
     * Singletone instance
     * @var ValidationManager 
     */
    private static $instance;
    
    private $initialized = false;
    
    private $validators = [];
    
    /**
     * 
     * @return ValidationManager
     */
    public static function getInstance()
    {
        return static::$instance ?: new static();
    }
    
    protected function __construct() {
        $this->initialize();
    }
    
    public function initialize()
    {
        if($this->initialized) {
            return;
        }
        
        $this->initialized = true;
    }
    
    public function getResult()
    {
       list($type, $data, $options) = func_get_args();
       
       if(isset($this->validators[$type])) {
           return $this->validators[$type]->invalidate($data, $options);
       } else {
           if(class_exists($validatorClass = 'App\\Model\\Validator\\'.$type)) {
               $this->validators[$type] = ($validator = new $validatorClass);
               return $validator->invalidate($data, $options);
           } else {
               return $data;
           }
       }
    }
}

?>
