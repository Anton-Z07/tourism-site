<?php

namespace App\Model\Manager\Routes;

use App\Model\Manager\ValidationManager;

class FieldRoute extends AbstractBaseRoute implements ChildishRouteInterface {
    
    protected $perent;
    protected $field;
    protected $options;
    
    public function __construct($incomingValue, $perent, $field, $options)
    {
        $this->parent = $perent;
        $this->field = $field;
        $this->options = $options;
        parent::__construct($incomingValue);
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function getField()
    {
        return $this->field;
    }
    
    public function getOptions()
    {
        return $this->field;
    }
    
    protected function initialize()
    {
        $this->processParse();
    }
    
    protected function processParse()
    {
        $this->value = $this->options['validator'] ?
                ValidationManager::getInstance()->getResult($this->options['validator'], $this->incomingValue, $this->options) :
                $this->incomingValue;
    }
    
    public function onParentInit()
    {
    }
    
    public function onParentModifier()
    {
        
    }
    
    public function onParentEnd()
    {
    }
}

?>
