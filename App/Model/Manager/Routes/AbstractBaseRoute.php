<?php

namespace App\Model\Manager\Routes;

abstract class AbstractBaseRoute {
    
    protected $value;
    protected $incomingValue;
    protected $config = [];
    
    public function __construct($incomingValue)
    {
        $this->incomingValue = $incomingValue;
        $this->initialize();
    }
    
    public function getConfig()
    {
        return $this->config;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    protected function initialize()
    {
        
    }
    
    protected function processParse()
    {
        
    }
    
    protected function processInit()
    {
        
    }
    
    protected function processSave()
    {
        
    }
    
    protected function processModifier()
    {
        
    }
    
    protected function processEnd()
    {
        
    }
}
