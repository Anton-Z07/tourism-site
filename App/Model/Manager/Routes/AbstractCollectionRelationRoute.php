<?php

namespace App\Model\Manager\Routes;

abstract class AbstractCollectionRelationRoute extends CollectionRoute implements ChildishRelationRouteInterface {
    
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
        $this->foreignKey = $this->options['foreignkey'];
        $this->selfKey = $this->options['selfkey'] ?: 'id';
        parent::initialize();
    }
    
}
