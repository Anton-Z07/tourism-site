<?php
namespace v1;

use App\Application,
    App\Model\Utils\ImageUploadProcessor,
    App\Model\Utils\MapFileUploadProcessor,
    App\Model\Mapper\ConditionsMap,
    App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper,
    Luracast\Restler\RestException,
    App\Model\Manager\UploadManager;

class FileGroup extends AbstractController
{
    /**
     * @return array
     */
    public function post($data = null)
    {
        if(!class_exists($this->entity)) {
            throw new RestException(400, "'$this->entity' entity missing");
        }
        if(count($_FILES) < 1) {
            throw new RestException(400, "No files to upload");
        }
        
        foreach($_FILES as $alies=>$f) {
            $file = UploadManager::getInstance()->fileViaUpload($alies, UploadManager::SITE_FILE);
            break;
        }
        
        if(!$file) {
            throw new RestException(500, 'File type unsupported!');
        }
        
        if($file->getOptions()['handling'] == 'images') {
            $collection = ImageUploadProcessor::createImagesBySizes($file, Application::getImageUploadSizes());
        } elseif($file->getOptions()['handling'] == 'maps') {
            $collection = MapFileUploadProcessor::createMapFile($file);
        } else {
            throw new RestException(500);
        }

        $result = UploadManager::getInstance()->getResult($collection, UploadManager::SITE_FILE);
        
        $conditionsMap = new ConditionsMap();
        $conditionsMap->setIncludeHidden(true);
        $conditionsMap->setController(get_class($this));
        //print_r(Application::getSQLLog());
        return static::resultModefire($result->toArray(), $conditionsMap->getOptionsMap());
    }
    
    /**
     * @param int $id {@from path}
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
        
        if(!isset($_FILES['files']) || !count($_FILES['files'])) {
            throw new RestException(400, "No files to upload");
        }
        
        $file = UploadManager::getInstance()->fileViaUpload('files', UploadManager::SITE_FILE);
        
        if(!$file) {
            throw new RestException(500, 'File type unsupported!');
        }
        
        if($file->getOptions()['handling'] == 'images') {
            $collection = ImageUploadProcessor::createImagesBySizes($file, Application::getImageUploadSizes());
        } elseif($file->getOptions()['handling'] == 'maps') {
            $collection = MapFileUploadProcessor::createMapFile($file);
        } else {
            throw new RestException(500);
        }

        $result = UploadManager::getInstance()->getResult($collection, UploadManager::SITE_FILE, intval($id));
        
        $conditionsMap = new ConditionsMap();
        $conditionsMap->setIncludeHidden(true);
        $conditionsMap->setController(get_class($this));
        //print_r(Application::getSQLLog());
        return static::resultModefire($result->toArray(), $conditionsMap->getOptionsMap());
    }
    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\FileGroupEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'name' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\SiteString',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'inject' => 'ru',
                    'foreignkey' => 'name_id'
                 ],
                'resource_type' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => [
                        0 => 'Неопределён',
                        1 => 'Гео. Главная',
                        2 => 'Гео. Мобильная',
                        3 => 'Гео. Мобильная карта',
                        4 => 'Гео. Заглушка',
                        5 => 'Гео. Карта',
                        6 => 'Гео. Флаг',
                        7 => 'Достоприм.',
                        8 => 'Фишки',
                        9 => 'Вел. Люди',
                        10 => 'Кухня',
                        11 => 'Свойст. Достоприм.',
                        12 => 'Новости',
                        13 => 'Товары',
                        14 => 'Инфоблоки. Главная',
                        15 => 'Инфоблоки. Вторая',
                        16 => 'Конкурсы',
                        17 => 'Конкурсы Иконка'
                    ]],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'files' => [
                    'relation' => BaseMapper::RELATION_HASMANY,
                    'resource' => 'v1\File',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'depth' => 3
                 ],
                'created_at' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
                'updated_at' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
            ],
        ];
    }
}

