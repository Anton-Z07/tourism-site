<?php

namespace App\Model\Manager\Routes;

use App\Model\Config,
    Luracast\Restler\RestException,
    Illuminate\Support\Contracts\ArrayableInterface;

abstract class AbstractEntireRoute extends AbstractBaseRoute implements ParentalRouteInterface, ArrayableInterface {
    
    protected $controller;
    protected $primaryKey;
    
    public function __construct($incomingValue, $controller) {
        
        $this->controller = $controller;
        
        parent::__construct($incomingValue);
    }
    
    public function getController()
    {
        return $this->controller;
    }
    
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
    
    protected function initialize()
    {
        $this->config = Config::getControllerConfig($this->controller);
        $this->primaryKey = $this->config['primary'];
    }
    
    protected function getFieldOptions($field)
    {
        if(!isset($this->config['fields'][$field])) {
            return false;
        }
        
        return $this->config['fields'][$field];
    }
    
    abstract protected function createChild();
}
