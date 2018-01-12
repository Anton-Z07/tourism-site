<?php

namespace App\Model\Manager\Routes;

class BelongsToRoute extends AbstractEntityRelationRoute {
    
    
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
    
    protected function processInit()
    {
        if($this->getMode() == static::MODE_CREATE) {
            $parentValue = $this->getParent()->getValue();
            if(isset($parentValue[$this->foreignKey]) && intval($parentValue[$this->foreignKey]) > 0) {
                $this->incomingValue[$this->selfKey] = $this->createChild($this->selfKey, strval($parentValue[$this->foreignKey]));
                $this->setMode(static::MODE_UPDATE);
            }
        }
        
        parent::processInit();
    }
}

?>
