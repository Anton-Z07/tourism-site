<?php

namespace App\Model;

use App\Controller\AbstractController;

class Config {
    
    private static $controller = [];
    
    private static $config = [];
    
    private static $defaultControllerOptions = [
        'entity' => 'App\Entity\EmptyEntity',
        'primary' => 'id',
        'fields' => []
    ];
    
    private static $defaultFieldOptions = [
        'validator'=>'Int',
        'options' => [],
        'access' => 0,
        'resource' => null,
        'relation' => 0,
        'modefire' => null,
        'depth' => 3,
        'inject' => null,
        /*'filter' => null,
        'sort' => '',*/
        'conditions' => [],
        'nested' => 5,
        'pivot' => false,
        'foreignkey' => false,
        'selfkey' =>false
    ];
    
    /**
     * 
     * @param string $controller
     * @return AbstractController
     */
    public static function getController($controller)
    {
        if(!isset(static::$controller[$controller])) {
            if(!class_exists($controller)) {
                return false;
            }
            static::$controller[$controller] = new $controller;
        }
        
        return static::$controller[$controller];
    }
    
    public static function getControllerConfig($controller)
    {
        if(!isset(static::$config[$controller])) {
            
            $instance = static::getController($controller);
            
            if(!$instance) {
                return false;
            }
            
            $config = $instance->getConfig();
            
            foreach ($config['fields'] as $field=>$options) {
                $config['fields'][$field] = array_replace_recursive(static::$defaultFieldOptions, $options);
            }
            
            static::$config[$controller] = array_replace_recursive(static::$defaultControllerOptions, $config);
        }
        
        return static::$config[$controller];
    }
    
    public static function getFieldsConfig($controller)
    {
        return ($controllerConfig = static::getControllerConfig($controller)) ? $controllerConfig['fields'] : false;
    }
}

?>
