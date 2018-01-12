<?php

namespace App\Model\Manager\Routes;

class HasOneRoute extends AbstractEntityRelationRoute {

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
    
    protected function processInit()
    {
        if($this->getMode() == static::MODE_CREATE) {
            parent::processInit();
        } else {
            $parentValue = $this->getParent()->getValue();
            if(isset($parentValue[$this->selfKey]) && intval($parentValue[$this->selfKey]) > 0) {
                $collection = (new $this->entity)->newQuery()
                    ->where($this->foreignKey, '=', intval($parentValue[$this->selfKey]))
                    ->take(1)
                    ->get();
                
                if($collection->count() > 0) {
                    $this->value = $collection->first();
                    $this->setMode(static::MODE_UPDATE);
                }
            }
        }
    }
    
}

?>
