<?php

namespace App;

use Illuminate\Database\Capsule\Manager as Capsule,
    Luracast\Restler\Restler,
    Luracast\Restler\Defaults,
    Luracast\Restler\Format\UploadFormat,
    Illuminate\Events\Dispatcher;
        
class Application {
    
    protected static $config = [];
    
    /**
     *
     * @var Capsule
     */
    protected static $capsule;
    
    /**
     * 
     * @var Dispatcher
     */
    protected static $dispatcher;
    
    /**
     *
     * @var Restler 
     */
    protected static $restler;
    
    /**
     * 
     * @return Capsule
     */
    public static function getCapsule()
    {
        return static::$capsule;
    }
    
    public static function getSQLLog()
    {        
        return static::$capsule->connection()->getQueryLog();
    }
    
    public static function getRequestFormatMIME()
    {
        if(!static::$restler) {
            return false;
        }
        return static::$restler->responseFormat->getMIME();
    }
    
    public static function getMarketFromCode($marketCode)
    {
        return isset(static::$config['markets'][$marketCode]) ? static::$config['markets'][$marketCode] : false;
    }
    
    public static function getDataPath()
    {
        return static::$config['paths']['data'];
    }
    
    public static function getUploadPath()
    {
        return static::$config['paths']['upload'];
    }
    
    public static function getImageUploadSizes()
    {
        return static::$config['files']['imagesizes'];
    }
    
    public static function getUserImageUploadSizes()
    {
        return static::$config['userfiles']['imagesizes'];
    }
    
    public static function getUploadMimi()
    {
        return static::$config['files']['mimi'];
    }
    
    public static function getUserUploadMimi()
    {
        return static::$config['userfiles']['mimi'];
    }
    
    public static function getSearchWords()
    {
        return static::$config['search']['words'];
    }

    public function __construct($options = array())
    {
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            set_include_path('..;'.  get_include_path());
        } else {
            set_include_path('..:'.  get_include_path());
        }
        
        include 'vendor/restler.php';
        
        spl_autoload_register([$this, 'classLoader']);
        
        $this->loadConfig();
        
        static::$config = array_merge(static::$config, $options);
    }
    
    public function run() {
        ini_set('display_errors', static::$config['displayErrors']);
        error_reporting(static::$config['errorReporting']);
        static::$capsule = new Capsule;
        static::$capsule->addConnection(static::$config['db']);
        static::$dispatcher = new Dispatcher();
        static::$capsule->setEventDispatcher(static::$dispatcher);
        $this->initObservers();
        //static::$capsule->getContainer()->bind('paginator', 'Illuminate\Pagination\Paginator');
        //static::$capsule->getContainer()->bind('finder', 'Illuminate\View\FileViewFinder');
        static::$capsule->setAsGlobal();
        static::$capsule->bootEloquent();
        
        //Defaults::$useUrlBasedVersioning = true;
        //Defaults::$apiVendor = 'cultturist';
        //Defaults::$useVendorMIMEVersioning = true;
        Defaults::$cacheDirectory = static::$config['paths']['data'].'/cache';

        static::$restler = new Restler(static::$config['productionMode'], static::$config['refreshCache']);
        static::$restler->setAPIVersion(static::$config['version']);
        static::$restler->setSupportedFormats(/*'App\View\Strategy\JsonStrategy', */ 'JsonFormat', 'App\View\Strategy\HalJsonStrategy','UploadFormat');
        static::$restler->setOverridingFormats('HtmlFormat');
        
        UploadFormat::$allowedMimeTypes = ['image/jpeg', 'image/png', 'application/zip', 'application/octet-stream'];
        UploadFormat::$maximumFileSize = 104857600; //100M
                
        foreach(static::$config['controller'] as $controller=>$path) {
            static::$restler->addAPIClass($controller, $path);
        }
        static::$restler->handle();
    }
    
    protected function initObservers()
    {
        $disp = static::$dispatcher;
        $t = function($l) {
            print_r($l);
            echo "<br />\r\n";
        };
        static::$dispatcher->listen("*", function() use($disp, $t) {
            $event = $disp->firing();
            $args = func_get_args();
            @list($subject, $context) = explode(':', $event);
            if(!$subject) {
                return;
            }
            list($modul, $path) = explode('.', $subject);
            if(!$modul || !$path) {
                return;
            }
            if($modul == 'illuminate') {
                
            } elseif($modul =='eloquent') {
                $matches = [];
                $pattern = '#(\w+)?$#si';
                $observerClass = '';
                $observerMethod = $path;
                $entity = $args[0];
                if(!preg_match($pattern, trim($context), $matches)) {
                    return;
                }
                if(!isset($matches[1])) {
                    return;
                }
                if($matches[1] == 'Pivot') {
                    $observerMethod = 'pivot_'.$observerMethod;
                    if(!preg_match($pattern, class_basename($entity->getParent()), $matches)) {
                        return;
                    }
                    if(!isset($matches[1])) {
                        return;
                    }
                    $observerClass = $matches[1];
                } else {
                    $observerClass = $matches[1];
                }
                if(!strpos($observerClass, 'Entity')) {
                    return;
                }
                $observerClass = 'App\\Observers\\'.str_replace('Entity', 'Observer', $observerClass);
                if(!class_exists($observerClass, true)) {
                    return;
                }
                $ins = new $observerClass();
                $observerMethod = studly_case($observerMethod);
                if(!method_exists($ins, $observerMethod)) {
                    return;
                }
                return $ins->{$observerMethod}($entity);
            }
        });
    }


    protected function loadConfig() {
        
        $configFile = __DIR__.'/config/config.php';
        
        if(file_exists($configFile)) {
            $config = include $configFile;
            if(is_array($config)) {
                 static::$config = array_merge(static::$config, $config);
            }
        }
    } 
    
    private function classLoader($className)
    {
        $filePath = __DIR__.str_replace('\\', '/', strstr($className, '\\')).'.php';
        if(file_exists($filePath)) {
            include $filePath;
            return;
        }
        
        $filePath = __DIR__.'/Controller/'.str_replace(['\\', '/', '\\\\'], '/', $className).'.php';
        if(file_exists($filePath)) {
            include $filePath;
            return;
        }
    }
}

?>
