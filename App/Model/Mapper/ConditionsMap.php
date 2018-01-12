<?php

namespace App\Model\Mapper;

use App\Model\Config;

class ConditionsMap {
    
    const TYPE_SORT = 'sort';
    const TYPE_FILTER = 'filter';
    
    private $name;
    
    private $parent;
    
    private $controller;
    
    private $routes = array();
    
    private $fieldOptions;
    
    private $conditions = array();
    
    protected $includeHidden = false;
    
    protected $depth = 0;


    public function __construct($parent = null, $name = null, $fieldOptions = null)
    {
        if(!is_null($parent)) {
            $this->parent = $parent;           
        }
        
        if(!is_null($name)) {
            $this->name = $name;           
        }
        
        if(!is_null($fieldOptions)) {
            $this->fieldOptions = $fieldOptions;
        } 
    }
    
    public function getFieldOptions()
    {
        return $this->fieldOptions;
    }
    
    public function isRoot()
    {
        return !isset($this->parent);
    }
    
    public function setDepth($depth)
    {
        $this->depth = $depth;
    }
    
    public function setController($controller)
    {
        $this->controller = $controller;
        
        if(!($config = Config::getFieldsConfig($controller))) {
            return;
        }
        foreach($config as $field=>$fieldOptions) {
            if($fieldOptions['relation'] > BaseMapper::RELATION_NONE && ($this->includeHidden || $fieldOptions['access'] > BaseMapper::CONTROL_HIDDEN) && $this->depth < 2) {
                $this->getRouteFromPath($field);
            }
        }
    }
    
    public function setIncludeHidden($val)
    {
        $this->includeHidden = $val;
    }
    
    public function getController()
    {
        return $this->controller;
    }
    
    public function getParentController()
    {
        return $this->parent ? $this->parent->getController() : false;
    }
    
    public function getRouteFromPath($path)
    {
        if(!is_string($path)) {
           return false; 
        }
        
        if(strlen($path) < 1) {
            return $this;
        }
        
        return $this->getRouteFromArray(explode('.', $path));
    }
    
    public function getRouteFromArray($route)
    {        
        if(!isset($route[0])) {
            return $this;
        }
        
        $field = array_shift($route);
        
        if(!isset($this->routes[$field])) {
            $fieldOptions = $this->loadFieldOptions($field);
            
            if(!$fieldOptions) {
                return false;
            }
            
            $newChildRoute = new static($this, $field, $fieldOptions);
            $newChildRoute->setDepth($this->depth + 1);
            $newChildRoute->setIncludeHidden($this->includeHidden);
            $this->routes[$field] = $newChildRoute;
            
            if(!is_null($fieldOptions['resource'])) {
                $newChildRoute->setController($fieldOptions['resource']);
            }
            
        }
        
        return $this->routes[$field]->getRouteFromArray($route);
    }
    
    public function attachFields($fieldsArray)
    {
        foreach($fieldsArray as $path=>$value) {
            if($route = $this->getRouteFromPath($path)) {
                if($fieldOptions = $route->getFieldOptions()) {
                    if($fieldOptions['relation'] > BaseMapper::RELATION_NONE) {
                        if($fieldOptions['inject']) {
                            $route = $this->getRouteFromPath($path.'.'.$fieldOptions['inject']);
                        } else if($controller = $route->getController()) {
                            if($config = Config::getControllerConfig($controller)) {
                                if($config['primary']) {
                                    $route = $this->getRouteFromPath($path.'.'.$config['primary']);
                                }
                            }
                        }
                    }
                }
                $route->addField();
            }
        }
    }
    
    public function attachOrders($ordersArray)
    {
        foreach($ordersArray as $path=>$value) {
            if($route = $this->getRouteFromPath($path)) {
                if($fieldOptions = $route->getFieldOptions()) {
                    if($fieldOptions['relation'] > BaseMapper::RELATION_NONE) {
                        if($fieldOptions['inject']) {
                            $route = $this->getRouteFromPath($path.'.'.$fieldOptions['inject']);
                        } else if($controller = $route->getController()) {
                            if($config = Config::getControllerConfig($controller)) {
                                if($config['primary']) {
                                    $route = $this->getRouteFromPath($path.'.'.$config['primary']);
                                }
                            }
                        }
                    }
                }
                $route->addOrder($value);
            }
        }
    }
    
    public function attachFilter($filtersArray)
    {
        foreach($filtersArray as $path=>$value) {
            if($route = $this->getRouteFromPath($path)) {
                if($fieldOptions = $route->getFieldOptions()) {
                    if($fieldOptions['relation'] > BaseMapper::RELATION_NONE) {
                        if($fieldOptions['inject']) {
                            $route = $this->getRouteFromPath($path.'.'.$fieldOptions['inject']);
                        } else if($controller = $route->getController()) {
                            if($config = Config::getControllerConfig($controller)) {
                                if($config['primary']) {
                                    $route = $this->getRouteFromPath($path.'.'.$config['primary']);
                                }
                            }
                        }
                    }
                }
                $route->addFilter($value);
            }
        }
    }
    
    public function addField()
    {
        if(!$this->parent) {
            return;
        }
        
        if($this->access < BaseMapper::CONTROL_ACL) {
            $this->access = BaseMapper::CONTROL_ACL;
        }
        
        $this->parent->addField();
    }
    
    public function addOrder($sort)
    {
        if(!$this->parent) {
            return;
        }
        if($this->access < BaseMapper::CONTROL_ACL) {
            $this->access = BaseMapper::CONTROL_ACL;
        }
        if($this->pivot) {
            $parentController = $this->parent->getParentController();
            if(is_array($this->pivot)) {
                if(in_array($parentController, $this->pivot)  === false) {
                    return;
                }
            } elseif($parentController != $this->pivot) {
                return;
            }
        }
        $this->parent->addCondition($this->name, $sort, static::TYPE_SORT);
    }
    public function addFilter($value)
    {
        if(!$this->parent) {
            return;
        }
        
        if($this->access < BaseMapper::CONTROL_ACL) {
            $this->access = BaseMapper::CONTROL_ACL;
        }
        
        if($this->pivot) {
            $parentController = $this->parent->getParentController();
            if(is_array($this->pivot)) {
                if(in_array($parentController, $this->pivot)  === false) {
                    return;
                }
            } elseif($parentController != $this->pivot) {
                return;
            }
        }
        $this->parent->addCondition($this->name, $value, static::TYPE_FILTER);
    }
    
    public function addCondition($field, $condition, $type)
    {
        if(!$this->getRouteFromPath($field)) {
            return;
        }
        if(!isset($this->conditions[$field])) {
            if(isset($this->fieldOptions['conditions'])) {
                if(isset($this->fieldOptions['conditions'][$field])) {
                    $this->conditions[$field] = $this->fieldOptions['conditions'][$field];
                }
            }
            $this->conditions[$field] = ['sort' => false, 'operation' => false, 'value' => false, 'nested' => false];
        }
        
        if($this->parent) {
            $this->conditions[$field]['nested'] = $this->nested;
        }
        
        if($type == static::TYPE_FILTER) {
            $this->conditions[$field]['operation'] = $condition['operation'];
            $this->conditions[$field]['value'] = $condition['value'];
        } else if($type == static::TYPE_SORT) {
            $this->conditions[$field]['sort'] = $condition;
        }
        
        $this->addField();
    }
    
    public function hasRouteConditionFilter() 
    {
        foreach($this->routes as $route) {
            $conditions = $route->getConditions();
            foreach($conditions as $condition) {
                if($condition['operation']) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    public function getConditions()
    {
        return $this->conditions;
    }
    
    public function getConditionsMap()
    {
        $resultMap = array('.' => array(
            'fields' => $this->conditions,
            'nested' => 0)
        );
        
        return array_merge($resultMap, $this->getChildConditionsMap());
    }
    
    public function getChildConditionsMap($parentPath = '')
    {
        $resultMap = array();
        
        foreach($this->routes as $field=>$route) {
            if($route->access > BaseMapper::CONTROL_HIDDEN && $route->relation > BaseMapper::RELATION_NONE) {
                $path = (strlen($parentPath) > 0 ? $parentPath.'.' : '').$field;
                $resultMap[$path] = array('fields' => $route->getConditions(), 'nested' => $this->parent ? $this->nested : 5);
                $resultMap = array_merge($resultMap, $route->getChildConditionsMap($path));
            }
        }
        return $resultMap;
    }
    
    public function getOptionsMap($parentPath = '')
    {
        $optionsMap = [];
        
        if(!($config = Config::getFieldsConfig($this->controller))) {
            return $optionsMap;
        }
        foreach($config as $field=>$fieldOptions) {
            $path = (strlen($parentPath) > 0 ? $parentPath.'.' : '').$field;
            $optionsMap[$path] = $fieldOptions;
                
            if(isset($this->routes[$field]) && $fieldOptions['relation'] > BaseMapper::RELATION_NONE) {
                $optionsMap = array_merge($optionsMap, $this->routes[$field]->getOptionsMap($path));
            }
        }
        return $optionsMap;
    }
    
    public function __get($name)
    {
        if(is_null($this->fieldOptions) || !isset($this->fieldOptions[$name])) {
            throw new \Exception("Property '$name' not exists!");
        }
        
        return $this->fieldOptions[$name];
    }
    
    public function __set($name, $value) {
        if(is_null($this->fieldOptions) || !isset($this->fieldOptions[$name])) {
            throw new \Exception("Property '$name' not exists!");
        }
        
        $this->fieldOptions[$name] = $value;
    }
    
    private function loadFieldOptions($field)
    {
       $config = Config::getFieldsConfig($this->controller);
       
       if(!isset($config[$field])) {
           return false;
       }
       
       return $config[$field];
    }
}

?>
