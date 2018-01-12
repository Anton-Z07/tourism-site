<?php

namespace App\Model\Manager\Routes;

use Luracast\Restler\RestException,
    App\Model\Utils\ArrayUtil;

class HasManyRoute extends AbstractCollectionRelationRoute {
    
    public function getValue()
    {
        $result = [];
        foreach ($this->value as $item) {
            $result[] = $item->getValue();
        }
        return $result;
    }
    
    public function dispatchChildInit()
    {
        $params = func_get_args();
        array_walk ($this->incomingValue, function($item) use($params) {
            call_user_func_array ([$item, 'onParentInit'], $params);
        });
    }
    
    public function dispatchChildModifier()
    {
        $params = func_get_args();
        array_walk ($this->incomingValue, function($item) use($params) {
            call_user_func_array ([$item, 'onParentModifier'], $params);
        });
    }
    
    public function dispatchChildEnd()
    {
        $params = func_get_args();
        array_walk ($this->incomingValue, function($item) use($params) {
            call_user_func_array ([$item, 'onParentEnd'], $params);
        });
    }
    
    public function onParentInit()
    {
    }
    
    public function onParentModifier()
    {
        $this->processParse();
        $this->processInit();
        $this->dispatchChildInit();
        $this->processModifier();
        $this->dispatchChildModifier();
    }
    
    public function onParentEnd()
    {
        $this->processEnd();
        $this->dispatchChildEnd();
    }
    
    protected function createChild()
    {
        list($data) = func_get_args();
        return new HasManyChildRoute($data, $this, $this->field, $this->options);
    }
}

?>
