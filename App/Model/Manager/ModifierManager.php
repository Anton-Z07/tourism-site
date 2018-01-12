<?php

namespace App\Model\Manager;

use App\Model\Manager\Routes\CollectionRoute,
    App\Model\Manager\Routes\EntityRoute;

final class ModifierManager implements ManagerInterface{
    
    /**
     * Singletone instance
     * @var ModifierManager 
     */
    private static $instance;
    
    private $initialized = false;
    
    /**
     * 
     * @return ModifierManager
     */
    public static function getInstance()
    {
        return static::$instance ?: new static();
    }
    
    protected function __construct() {
        $this->initialize();
    }
    
    public function initialize()
    {
        if($this->initialized) {
            return;
        }
        
        $this->initialized = true;
    }
    
    public function getResult()
    {
       list($asCollection, $controller, $data) = func_get_args();
       
       return $asCollection ? 
               (new CollectionRoute($data, $controller))->process() : 
                   (new EntityRoute($data, $controller))->process();
    }
}

?>
