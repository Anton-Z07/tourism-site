<?php

namespace App\Model\Manager\Routes;

use Luracast\Restler\RestException,
    App\Model\Config;

abstract class AbstractEntityRelationRoute extends EntityRoute implements ChildishRelationRouteInterface {
    
    protected $perent;
    protected $field;
    protected $options;
    protected $foreignKey;
    protected $selfKey;

    public function __construct($incomingValue, $perent, $field, $options)
    {
        $this->parent = $perent;
        $this->field = $field;
        $this->options = $options;
        
        parent::__construct($incomingValue, $options['resource']);
    }
    
    public function getField()
    {
        return $this->field;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function getForeignKey()
    {
        return $this->foreignKey;
    }
    
    public function getSelfKeyKey()
    {
        return $this->selfKey;
    }
    
    abstract public function onParentInit();
    abstract public function onParentModifier();
    abstract public function onParentEnd();
    
    protected function initialize()
    {
        parent::initialize();
        $this->foreignKey = $this->options['foreignkey'];
        $this->selfKey = $this->options['selfkey'] ?: 'id';
    }
    
    protected function processParse()
    {
        if(is_string($this->incomingValue) || is_int($this->incomingValue)) {
            if($injectField = $this->getOptions()['inject']) {                
                if(is_int($this->incomingValue) && $this->incomingValue > 0) {
                    $this->incomingValue = [
                        "$this->primaryKey" => $this->createChild($this->primaryKey, $this->incomingValue)
                    ];
                    $this->setMode(static::MODE_SELECT);
                    return;
                }
                
                $this->incomingValue = [
                   "$injectField" => $this->createChild($injectField, $this->incomingValue)
                ];

                $this->setMode(static::MODE_CREATE);
            } else {
                $this->incomingValue = intval($this->incomingValue);
                if($this->incomingValue < 1) {
                    throw new RestException(400, 'Given index value '.$this->incomingValue.' is corrapted');
                }
                $this->incomingValue = [
                    "$this->primaryKey" => $this->createChild($this->primaryKey, $this->incomingValue)
                ];
                $this->setMode(static::MODE_SELECT);
                return;
            }            
        } else if(is_array($this->incomingValue)) {
            foreach($this->incomingValue as $field=>$value) {
                if(is_null($value)) {
                    unset($this->incomingValue[$field]);
                    continue;
                }
                
                $this->incomingValue[$field] = $this->createChild($field, $value);
            }
            
            if(isset($this->incomingValue[$this->primaryKey])) {
                $this->setMode(static::MODE_UPDATE);
            } else {
                $this->setMode(static::MODE_CREATE);
            }
            
        } else {
            throw new RestException(400, 'Unsuported type');
        }
    }
    
    protected function dispatchModifier()
    {
        Config::getController($this->controller)->eventModifier($this->value, $this->parent, $this->field);        
    }
    
    protected function dispatchSave()
    {
        Config::getController($this->controller)->eventSave($this->value, $this->parent, $this->field);
    }
    
    protected function dispatchEnd()
    {
        Config::getController($this->controller)->eventEnd($this->value, $this->parent, $this->field);
    }
}
