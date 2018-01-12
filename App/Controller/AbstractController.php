<?php

namespace App\Controller;

use Illuminate\Database\Eloquent\Builder,
    Luracast\Restler\RestException,
    App\Model\Mapper\BaseMapper,
    App\Model\Mapper\ConditionsMap,
    App\Model\Utils\ArrayUtil,
    App\Model\Config,
    App\Application,
    App\Model\Manager\UploadManager,
    App\Model\Manager\ModifierManager;

abstract class AbstractController {
    
    protected static $operations = [
        'not' => '<>',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
        'eq' => '=',
        'in' => 'in',
        'notin' => 'not in',
        'like' => 'like',
        'notlike' => 'not like'
    ];
   
    /**
     * 
     * @var array 
     */
    protected $fields = [];
    
    /**
     * 
     * @var string 
     */
    protected $entity = '';
    
    /**
     * 
     * @var string 
     */
    protected $primary = '';
    
    /**
     * @var Builder
     */
    private $queryBuilder;
     
    
    public function __construct()
    {
        $config = $this->getConfig();
        $this->entity = $config['entity'];
        $this->primary = $config['primary'];
        $this->fields = $config['fields'];
    }
    
    /**
     * 
     * @return array
     */
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\Entity',
            'primary' => 'id',
            'fields' => []
        ];
    }
    
    public function eventModifier($entity, $parent = null, $parentField = null)
    {
        
    }
    
    public function eventSave($entity, $parent = null, $parentField = null)
    {
        
    }
    
    public function eventEnd($entity, $parent = null, $parentField = null)
    {
        
    }

    /**
     * @header Access-Control-Allow-Origin: *
     * @param string $filter {@from query}
     * @param string $order {@from query}
     * @param string $fields {@from query}
     * @param int $page {@from query}
     * @param int $pagesize {@from query}
     * param string $Format {@from head}
     * smart-auto-routing false
     * @return array
     */
    public function index($filter = '', $order = '', $fields = '', $page = null, $pagesize = null)
    {
        $page = intval($page) > 0 ? intval($page) : 1;
        $pageSize = intval($pagesize) > 0 ? intval($pagesize) : 20;
        
        if(!class_exists($this->entity)) {
            throw new RestException(400, "'$this->entity' entity missing");
        }
        
        $query = $this->getQuery();
        $cm = static::createConditionsMap(get_class($this), $fields, $filter, $order);
        
        return static::getCollection($query, $cm, $page, $pageSize, Application::getRequestFormatMIME() == 'application/hal+json');
    }
    
    /**
     * @header Access-Control-Allow-Origin: *
     * @param int $id {@from path}
     * @param string $fields {@from query}
     * @param string $options {@from query}
     * smart-auto-routing false
     * @return array
     */
    public function get($id, $filter = '', $order = '', $fields = '', $settings = '')
    {
        if(intval($id) < 1) {
            throw new RestException(400, "Missing 'id' param!");
        }
        
        if(!class_exists($this->entity)) {
            throw new RestException(400, "'$this->entity' entity missing");
        }
        
        $query = $this->getQuery();
        $cm = static::createConditionsMap(get_class($this), $fields, $filter, $order);
        
        return static::getSingle($query, $cm, $id, $settings);
    }
    
    /**
     * @param array $data {@from body}
     * smart-auto-routing false
     * @return array
     */
    public function post($data = null)
    {
        if(!class_exists($this->entity)) {
            throw new RestException(400, "'$this->entity' entity missing");
        }
        
        if(!is_array($data) || !count($data)) {
            throw new RestException(400, "No data to creating!");
        }

        return static::modifier(get_class($this), $data);
    }
    
    /**
     * @param int $id {@from path}
     * @param array $data {@from body}
     * smart-auto-routing false
     * @return array
     */
    public function put($id, $data = null)
    {
        if(intval($id) < 1) {
            throw new RestException(400, "Missing 'id' param!");
        }
        
        if(!class_exists($this->entity)) {
            throw new RestException(400, "'$this->entity' entity missing");
        }
        
        if(!is_array($data) || !count($data)) {
            throw new RestException(400, "No data to updating!");
        }
        
        return static::modifier(get_class($this), $data, $id);
    }
    
    /**
     * @param int $id {@from path}
     * smart-auto-routing false
     * @return array
     */
    public function delete($id)
    {
        if(intval($id) < 1) {
            throw new RestException(400, "Missing 'id' param!");
        }
    }
    
    /**
     * @return Builder Description
     */
    final public function getQuery()
    {
        if(!($this->queryBuilder instanceof Builder)) {
            $instance = new $this->entity();
            $this->queryBuilder = $instance->newQuery();
        }
        
        return $this->queryBuilder;
    }
    
    protected static function createConditionsMap($controller, $fields = '', $filter = '', $order = '')
    {  
        if(!is_string($fields) || (strlen($fields) > 0 && !preg_match('/^[\w\,\.]+$/u', $fields))) {
            throw new RestException(400, "Incoming request 'fields' param is bad!");
        }
        if(!is_string($filter) || (strlen($filter) > 0 && !preg_match('/^[\w\:\,\;\-\.\% ]+$/u', $filter))) {
            throw new RestException(400, "Incoming request 'filter' param is bad!");
        }
        if(!is_string($order) || (strlen($order) > 0 && !preg_match('/^[\w\:\;\_\.]+$/u', $order))) {
            throw new RestException(400, "Incoming request 'order' param is bad!");
        }
        
        $conditionsMap = new ConditionsMap();
        $conditionsMap->setController($controller);
        $conditionsMap->attachFields(static::parseFields($fields));
        $conditionsMap->attachFilter(static::parseFilter($filter));
        $conditionsMap->attachOrders(static::parseOrders($order));
        return $conditionsMap;
    }
    
    protected static function getCollection($query, $conditionsMap, $page, $pageSize, $pagenate = false)
    {
        $relations = [];
        foreach($conditionsMap->getConditionsMap() as $field=>$options) {
            if($field == '.') {
                foreach($options['fields'] as $subField=>$conditions) {
                    list($sort, $operation, $value) = array_values($conditions);
                    if($operation !== false) {
                        if(in_array($operation, ['in', 'not in'])) {
                            $query->getQuery()->whereIn($subField, $value, 'and', $operation == 'not in');
                        } else {
                            $query->where($subField, $operation, $value);
                        }
                    }
                    if($sort !== false) {
                        $query->orderBy($subField, $sort);
                    }
                }
                
            } else {
                if(count($options['fields']) > 0) {
                    foreach($options['fields'] as $subField=>$conditions) {
                        list($sort, $operation, $value, $nested) = array_values($conditions);
                        if($operation !== false) {
                            $query->whereHas($field, function($query) use($subField, $operation, $value) {
                                if(in_array($operation, ['in', 'not in'])) {
                                    $query->getQuery()->whereIn($subField, $value, 'and', $operation == 'not in');
                                } else {
                                    $query->where($subField, $operation, $value);
                                }
                            });
                        }
                    }
                    
                    $relations[$field] = function($query) use($options) {
                       foreach($options['fields'] as $subField=>$conditions) {
                            list($sort, $operation, $value, $nested) = array_values($conditions);
                            if($sort !== false) {
                               $query->orderBy($subField, $sort); 
                            }
                       }
                       //TODO: Бага архитектуры, непонятно как решать
                       //$query->take(intval($options['nested']));
                    };
                } else {
                    $relations[$field] = function($query) use($options) {
                        //TODO: Бага архитектуры, непонятно как решать
                        //$query->take(intval($options['nested']));
                    };
                }
            }
        }
        
        $total = $pagenate ? $query->get()->count() : 0;
        
        $result = $query->with($relations)
                ->skip(($page - 1) * $pageSize)
                ->take($pageSize)->get();
        //print_r(Application::getSQLLog());
        //print_r($conditionsMap->getConditionsMap());
        if($pagenate) {
            return [
                'total' => $total,
                'page' => $page,
                'pagesize' => $pageSize,
                'results' => static::resultModefire($result->toArray(), $conditionsMap->getOptionsMap())
            ];
        } else {
            return static::resultModefire($result->toArray(), $conditionsMap->getOptionsMap());
        }
    }
    
    protected static function getSingle($query, $conditionsMap, $id, $settings = '')
    {
        $relations = [];
        foreach($conditionsMap->getConditionsMap() as $field=>$options) {
            if($field != '.') {
                if(count($options['fields']) > 0) {                    
                    $relations[$field] = function($query) use($options) {
                       foreach($options['fields'] as $subField=>$conditions) {
                            list($sort, $operation, $value) = array_values($conditions);
                            if($sort !== false) {
                               $query->orderBy($subField, $sort); 
                            }
                            
                            if($operation !== false) {
                                if(in_array($operation, array('in', 'notin'))) {
                                    $query->getQuery()->whereIn($subField, $value, 'and', $operation == 'notin');
                                } else {
                                    $query->where($subField, $operation, $value);
                                }                                
                            }
                       }
                       //TODO: Бага архитектуры, непонятно как решать
                       //$query->take(intval($options['nested']));
                    };
                } else {
                    $relations[$field] = function($query) use($options) {
                        //TODO: Бага архитектуры, непонятно как решать
                        //$query->take(intval($options['nested']));
                    };
                }
            }
        }
        
        $result = $query->with($relations)->find($id);
        
        if(is_null($result)) {
            throw new RestException(404, "Object with id = '$id' not found!");
        }
        
        return static::resultModefire($result->toArray(), $conditionsMap->getOptionsMap());
    }
    
    protected static function modifier($controller, $data, $id = 0)
    {
        $isAssocArray = ArrayUtil::isAssoc($data);
        
        if($isAssocArray && $id > 0) {
            $primaryKey = Config::getControllerConfig($controller)['primary'];
            $data[$primaryKey] = $id;
        }
        
        $result = ModifierManager::getInstance()->getResult(!$isAssocArray, $controller, $data, $id);
        
        $conditionsMap = new ConditionsMap();
        $conditionsMap->setIncludeHidden(true);
        $conditionsMap->setController($controller);
        //print_r(Application::getSQLLog());
        return static::resultModefire($result->toArray(), $conditionsMap->getOptionsMap());
    }
    
    private static function parseFields($fields)
    {
        $result = array();
        foreach(explode(',', $fields) as $field) {
            $result[$field] = 0;
        }
        
        return $result;
    }
    
    private static function parseOrders($orders)
    {
        $result = array();
        foreach(explode(';', $orders) as $order) {
            if(substr_count($order, ':') != 1) {
                continue;
            }
            list($path, $sorting) = explode(':', $order);
            
            $sorting = strtolower($sorting);
            
            if(!in_array($sorting, array('asc', 'desc'))) {
                continue;
            }
            $result[$path] = strtolower($sorting);
        }
        
        return $result;
    }
    
    private static function parseFilter($filters)
    {
        $result = [];
        foreach(explode(';', $filters) as $filter) {
            if(substr_count($filter, ':') != 2) {
                continue;
            }
            
            list($path, $operation, $value) = explode(':', $filter);
            
            $operation = strtolower($operation);
            
            if(isset(static::$operations[$operation])) {
                $operation = static::$operations[$operation];
            }

            if(!in_array($operation, static::$operations)) {
                continue;
            }

            if(in_array($operation, ['in', 'not in'])) {
                if(is_string($value)) {
                    $value = explode(',', $value);
                }
                
                if(!is_array($value)) {
                    continue;
                }
            } else if(in_array($operation, ['like', 'notlike'])) {
                
                if(!is_string($value)) {
                    continue;
                }
                
                $searchStr = urldecode($value);
                
                if(mb_strlen($searchStr) === 2) {
                    if(!in_array(strtolower($searchStr), Application::getSearchWords())) {
                        throw new RestException(400, "Search string too less!");
                    }
                } elseif(mb_strlen($searchStr) < 3) {
                    throw new RestException(400, "Search string too less!");
                }
            }

            $result[$path] = compact('operation', 'value');
        }
        
        return $result;
    }
    
    protected static function resultModefire($data, $optionsMap = [], $parent = '')
    {
        if(!is_array($data)) {
            return $data;
        }
        
        if(ArrayUtil::isSequentialArray($data)) {
            foreach($data as $k=>$item) {
                $data[$k] = static::resultModefire($item, $optionsMap, $parent);
            }
        } else {
            //TODO: Hardcore
            if(isset($data['pivot'])) {
                $pivotArray = $data['pivot'];
                $data = array_merge($data, $pivotArray);
                unset($data['pivot']);
            }
            
            foreach ($data as $field=>$value) {
                $path = $parent.(strlen($parent) > 1 ? '.' : '').$field;
                 if(isset($optionsMap[$path])) {
                    /*if($optionsMap[$path]['access'] < static::CONTROL_ACL) {
                        unset($data[$field]);
                        continue;
                    }*/
                    
                    if(!is_array($value)) {
                        continue;
                    }
                    
                    if(ArrayUtil::isSequentialArray($value)) {
                        $data[$field] = static::resultModefire($value, $optionsMap, $path);
                    } else {
                        if(!is_null($optionsMap[$path]['inject'])) {
                            if(isset($data[$field][$optionsMap[$path]['inject']])) {
                                $data[$field] = $data[$field][$optionsMap[$path]['inject']];
                            }
                        } else {
                            $data[$field] = static::resultModefire($value, $optionsMap, $path);
                        }
                    }  
                } else if(strpos($path, 'pivot') !== false) {
                    
                } else {
                    unset($data[$field]);
                }
            }
        }
        return $data;
    }
    
    /**
    * Handle dynamic static method calls into the method.
    *
    * @param  string  $method
    * @param  array   $parameters
    * @return mixed
    */
    public static function __callStatic($method, $parameters)
    {
        $instance = new static;

        return call_user_func_array(array($instance, $method), $parameters);
    }
}

?>
