<?php

namespace App\Model\Manager\Routes;

use Luracast\Restler\RestException,
    App\Model\Mapper\BaseMapper,
    App\Model\Config;

class EntityRoute extends AbstractEntireRoute implements ParentalRouteInterface {
    
    const MODE_SELECT = 0;
    const MODE_CREATE = 1;
    const MODE_UPDATE = 2;
    
    protected $entity;
    
    private $mode = 1;
    
    public function process()
    {
        $this->processParse();
        $this->processInit();
        $this->dispatchChildInit();
        $this->processModifier();
        $this->processSave();
        $this->dispatchChildModifier();
        $this->processEnd();
        $this->dispatchChildEnd();
        return $this;
    }
    
    public function toArray()
    {
        if(!$this->value) {
            return [];
        }
        
        $result = $this->value->toArray();
        foreach($this->incomingValue as $field=>$item) {
            if($item instanceof AbstractEntireRoute) {
                $result[$field] = $item->toArray();
            }
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
    
    public function getMode()
    {
        return $this->mode;
    }
    
    public function setMode($mode)
    {
        $this->mode = $mode;
    }
    
    protected function initialize()
    {
        parent::initialize();
        $this->entity = $this->config['entity'];
    }
    
    protected function createChild()
    {
        list($field, $data) = func_get_args();
        
        if(!($options = $this->getFieldOptions($field))) {
            return false;
        }        
        
        switch($options['relation']) {
            case BaseMapper::RELATION_NONE:
                return new FieldRoute($data, $this, $field, $options);
            case BaseMapper::RELATION_BELONGSTO:
                return new BelongsToRoute($data, $this, $field, $options);
            case BaseMapper::RELATION_HASONE:
                return new HasOneRoute($data, $this, $field, $options);
            case BaseMapper::RELATION_HASMANY;
                return new HasManyRoute($data, $this, $field, $options);
            case BaseMapper::RELATION_BELONGSTOMANY;
                return new BelongsToManyRoute($data, $this, $field, $options);
        }
    }
    
    protected function processParse()
    {
        if(is_string($this->incomingValue)) {
            if(!is_numeric($this->incomingValue) || intval($this->incomingValue) < 0) {
                throw new RestException(400, 'Primary key invalid!');
            }
            
            $this->incomingValue = [
               "$this->primaryKey" => $this->createChild($this->primaryKey, $this->incomingValue)
            ];

            $this->setMode(static::MODE_SELECT);
        } else if(is_array($this->incomingValue)) {
            foreach($this->incomingValue as $field=>$value) {
                if(is_null($value)) {
                    unset($this->incomingValue[$field]);
                    continue;
                }
                
                if($child = $this->createChild($field, $value)) {
                    $this->incomingValue[$field] = $child;
                } else {
                    unset($this->incomingValue[$field]);
                }
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
    
    protected function processInit()
    {
        if($this->mode != static::MODE_CREATE) {
            $primaryKeyValue = $this->incomingValue[$this->primaryKey]->getValue();
            
            if($this->mode == static::MODE_UPDATE && $primaryKeyValue < 1) {
                $this->setMode(static::MODE_CREATE);
                return;
            }
            $collection = (new $this->entity)->newQuery()
                    ->where($this->primaryKey, '=', $primaryKeyValue)
                    ->take(1)
                    ->get();
            if($collection->count() > 0) {
                $this->value = $collection->first();
            } else {
                throw new RestException(400, "Bad idintity value! Object where idintity '$this->primaryKey' is '$primaryKeyValue' not found!");
            }
        }
    }
    
    protected function processModifier()
    {
        if($this->mode != static::MODE_SELECT) {
            if($this->mode == static::MODE_CREATE) {
                $this->value = new $this->entity;
            }
            foreach($this->incomingValue as $field=>$value) {
                if($value instanceof FieldRoute) {
                    $this->value[$field] = $value->getValue();
                } else if($value instanceof BelongsToRoute) {
                    $this->value->{$field}()->associate($value->getValue());
                }
            }
            
            $this->dispatchModifier();
        }
    }
    
    protected function processSave()
    {
        if($this->value) {
            $this->value->save();
        }
        $this->dispatchSave();
    }
    
    protected function processEnd()
    {
        foreach($this->incomingValue as $field=>$value) {
            if($value instanceof HasOneRoute) {
                $this->value->{$field}()->save($value->getValue());
            } elseif($value instanceof HasManyRoute) {
                $this->value->{$field}()->saveMany($value->getValue());
            } elseif($value instanceof BelongsToManyRoute) {
                $this->value->{$field}()->sync($value->getValue());
                if(($res = $this->value->{$field}()->first())) {
                        $res->pivot->touch();
                }
            }
        }
        
        $this->dispatchEnd();
    }
    
    protected function dispatchModifier()
    {
        Config::getController($this->controller)->eventModifier($this->value);        
    }
    
    protected function dispatchSave()
    {
        Config::getController($this->controller)->eventSave($this->value);
    }
    
    protected function dispatchEnd()
    {
        Config::getController($this->controller)->eventEnd($this->value);
    }
}

?>
