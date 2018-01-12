<?php

namespace App\Model\Manager\Routes;

use Luracast\Restler\RestException,
    App\Model\Utils\ArrayUtil;

class CollectionRoute extends AbstractEntireRoute implements ParentalRouteInterface {
    
    public function process()
    {
        $this->processParse();
        $this->processInit();
        $this->dispatchChildInit();
        /*$this->callAfterInit();
        $this->processingModifier();
        $this->callAfterModifier();
        $this->processingEnd();*/
        return $this;
    }
    
    public function toArray()
    {
        if(!$this->value) {
            return [];
        }
        
        $result = [];
        
        foreach($this->value as $item) {
            $result[] = $item->toArray();
        }
        
        return $result;
    }
    
    public function dispatchChildInit()
    {
        $params = func_get_args();
        array_walk ($this->incomingValue, function($item) use($params) {
            call_user_func_array ([$item, 'process'], $params);
        });
    }
    
    public function dispatchChildModifier()
    {
    }
    
    public function dispatchChildEnd()
    {
       
    }
    
    protected function createChild()
    {
        list($data) = func_get_args();
        return new EntityRoute($data, $this->controller);
    }
    
    protected function processParse()
    {
        if(is_string($this->incomingValue)) {            
            $this->incomingValue = explode(",", $this->incomingValue);           
            foreach($this->incomingValue as $k=>$item) {
                if(intval($item) < 1) {
                    throw new RestException(400, 'Id must by integer and great of 0!');
                }
                $this->incomingValue[$k] = $this->createChild(["$this->primaryKey" => intval($item)]);
            }
        } else if(is_array($this->incomingValue)) {
            if(ArrayUtil::isAssoc($this->incomingValue)) {
                $this->incomingValue = [$this->incomingValue];
            }
            
            foreach($this->incomingValue as $k=>$item) {
                if(!is_array($item)) {
                    if(intval($item) < 1) {
                        throw new RestException(400, 'Data must be object or integer that great of 0!');
                    }
                    $this->incomingValue[$k] = $this->createChild(["$this->primaryKey" => intval($item)]);
                } else {
                    foreach($item as $field => $value) {
                        if(!($fieldOptions = $this->getFieldOptions($field)) || $fieldOptions['pivot']) {
                            unset($item[$field]);
                        }
                    }
                    $this->incomingValue[$k] = $this->createChild($item);
                }
            }
        } else {
            throw new RestException(400, 'Unsuported type');
        }
    }
    
    protected function processInit()
    {
        $this->value = $this->incomingValue;
    }
}
