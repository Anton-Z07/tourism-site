<?php

namespace App\Model\Manager\Routes;

use Luracast\Restler\RestException,
    App\Model\Utils\ArrayUtil;

class BelongsToManyRoute extends AbstractCollectionRelationRoute {
    
    public function getValue()
    {
        $result = [];
        foreach($this->value as $item) {
            $result[
                $item['item']->getValue()[
                        $item['item']->getPrimaryKey()
                    ]
                ] = $item['pivot'];
        }
        
        return $result;
    }
    
    public function toArray()
    {
        if(!$this->value) {
            return [];
        }
        
        $result = [];
        
        foreach($this->value as $item) {
            $result[] = array_merge($item['item']->toArray(), $item['pivot']);
        }
        return $result;
    }
    
    public function dispatchChildInit()
    {
        $params = func_get_args();
        array_walk ($this->incomingValue, function($item) use($params) {
            call_user_func_array ([$item['item'], 'process'], $params);
        });
    }
    
    public function onParentInit()
    {
        $this->process();
    }
    
    public function onParentModifier()
    {
        
    }
    
    public function onParentEnd()
    {
        
    }
    
    protected function processParse()
    {
        if(is_string($this->incomingValue)) {            
            $this->incomingValue = explode(",", $this->incomingValue);           
            foreach($this->incomingValue as $k=>$item) {
                if(intval($item) < 1) {
                    throw new RestException(400, 'Id must by integer and great of 0!');
                }
                $this->incomingValue[$k] = ['item' => $this->createChild(["$this->primaryKey" => intval($item)]), 'pivot' => []];
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
                    $this->incomingValue[$k] = ['item' => $this->createChild(["$this->primaryKey" => intval($item)]), 'pivot' => []];
                } else {
                    $pivot = [];
                    foreach($item as $field => $value) {
                        $pivotField = false;
                        if(!($fieldOptions = $this->getFieldOptions($field)) || ($pivotField = $fieldOptions['pivot'])) {
                            $parentController = $this->getParent()->getController();
                            if(is_array($pivotField)) {
                                if(in_array($parentController, $pivotField)) {
                                     $pivot[$field] = $value;
                                }
                            } elseif($parentController == $pivotField) {
                                $pivot[$field] = $value;
                            }
                            unset($item[$field]);
                        }
                    }
                    $this->incomingValue[$k] = ['item' => $this->createChild($item), 'pivot' => $pivot];
                }
            }
        } else {
            throw new RestException(400, 'Unsuported type');
        }
    }
}

?>
