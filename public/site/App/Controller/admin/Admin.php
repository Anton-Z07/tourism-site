<?php

namespace App\Controller\Admin;

use Luracast\Restler\Format\HtmlFormat,
    Luracast\Restler\RestException,
    Luracast\Restler\Redirect,
    Luracast\Restler\Scope,
    App\Model\Utils\PackageProcessor,
    App\Entity\AreaEntity,
    App\Entity\PackageEntity,
    App\Application;

class Admin {
    
    function __construct()
    {
        HtmlFormat::$data['title'] = 'Админ панель';
    }
    
    /**
     * @view admin/index.twig {@value response}
     * @format HtmlFormat
     * @return array
     */
    final public function index()
    {
        $error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_SPECIAL_CHARS);
        return [
            'admin'=> static::isAdmin(),
            'error' => $error ?: false,
            'jsversion' => '1.0.0.5'
        ];
    }
    
    /**
     * @return array
     */
    public function getCorePackage()
    {
        $coreZipPath = Application::getUploadPath().'/packages/core.zip';
        
        if(!file_exists($coreZipPath)) {
            throw new RestException(404, "Core package not exists!");
        }
        
        return new PackageEntity([
            'name' => 'core',
            'hash' => md5(mt_srand()).md5(microtime()),
            'path' => str_replace(realpath('.'), '', $coreZipPath),
            'size' => filesize($coreZipPath),
            'created_at' => filectime($coreZipPath),
            'updated_at' => filemtime($coreZipPath)
        ]);
    }
    
    /**
     * @return array
     */
    public function postCorePackage()
    {
        return (new PackageProcessor())->createCorePackage(AreaEntity::where('area_type', '=', 0)->get()->modelKeys());
    }
    
    /**
     * @param string $start {@from query}
     * @param string $end {@from query}
     * @return array
     */
    public function getBindLandmark($start = '', $end = '')
    {
        $start = intval($start) < 1 ? 'null' : intval($start);
        $end = intval($end) < 1 ? 'null' : intval($end);
        
        $db = Application::getCapsule()->connection();
        
        if(!$db) {
            throw new RestException(500);
        }
        
        $result = $db->select($db->raw("select `bind_landmark2`($start, $end);"));
        
        if(is_array($result)) {
            $result = array_shift($result);
        }
        
        return [
            'iteration' => count($result) ? array_shift($result) : 0
        ];
    }

    /**
     * @return string
     */
    public function getBindEntities()
    {
//        include('/tasks/BindEntities.php');
//        $output = exec('php ../../../tasks/BindEntities.php');

        include('/var/www/cult-turist.ru/www/tasks/BindEntities.php');
        return 'ok!';
    }
    
    /**
     * @param string $id {@from query}
     * @return array
     */
    public function getRoundLandmark($id = '')
    {
        $id = intval($id) < 1 ? 0 : intval($id);
        
        $db = Application::getCapsule()->connection();
        
        if(!$db) {
            throw new RestException(500);
        }
        
        $result = $db->select($db->raw("select `round_labdmark2area`($id);"));
        
        if(is_array($result)) {
            $result = array_shift($result);
            
            if(count($result)) {
                $result = array_diff(explode('_', array_shift($result)), array(''));
                array_walk($result, function(&$item) {
                    $p = explode(':', $item);
                    $item = [
                        'id' => $p[0],
                        'distance' => $p[1]
                    ];
                });
            }
        } else {
            $result = [];
        }
        
        return array_values($result);
    }
    
    /**
     * 
     * @format HtmlFormat
     * @param string $login {@from body}
     * @param string $password {@from body}
     */
    final public function post($login = '', $password = '')
    {
        $error = '';
        if($login !== 'admin' || $password !== 'azsxde34') {
           $error = 'Неверный логин или пароль';
        } else {
            static::setData();
        }
        
        $port = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : '80';
        
        $https = $port == '443' ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') || // Amazon ELB
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');

        $baseUrl = ($https ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
        Redirect::to($baseUrl.'/admin', strlen($error) > 0 ? ['error' => $error] : []);
    }
    
    private static function isAdmin()
    {
        $cookie = filter_input(INPUT_COOKIE, 'brbr');
        if($cookie) {
            if(md5('128') == $cookie) {
                static::setData();
                return true;
            }
        }
        return false;
    }
    
    private static function setData()
    {
        setcookie('brbr', md5('128'), time() + 3600);
    }
}

?>
