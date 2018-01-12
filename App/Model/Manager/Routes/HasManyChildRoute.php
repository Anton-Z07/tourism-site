<?php

namespace App\Model\Manager\Routes;

class HasManyChildRoute extends HasOneRoute {
    
    protected function processInit()
    {
        if($this->getMode() == static::MODE_CREATE) {
            $parentValue = $this->getParent()->getParent()->getValue();
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
