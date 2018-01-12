<?php

namespace App;

require '/var/www/cult-turist.ru/www/vendor/autoload.php';
//require '/vendor/autoload.php'; // local path

use Illuminate\Database\Capsule\Manager as Capsule,
    Illuminate\Events\Dispatcher;

class DBConn {

    protected $capsule;
    protected $config = [];

    public function __construct()
    {
        $this->loadConfig();
        $this->setupCapsule();
    }

    protected function loadConfig()
    {
        $configFile = __DIR__ . '/config/db.php';

        if (file_exists($configFile)) {
            $config = include $configFile;
            if (is_array($config)) {
                $this->config = array_merge($this->config, $config);
            }
        }
    }

    public function setupCapsule()
    {
        $this->capsule = new Capsule;
        $this->capsule->setEventDispatcher(new Dispatcher());
        $this->capsule->addConnection($this->config['db_local']);
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }
} 