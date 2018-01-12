<?php
namespace v1;

use App\Application,
    App\Model\Utils\ImageUploadProcessor,
    App\Model\Mapper\ConditionsMap,
    App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper,
    App\Model\Manager\UploadManager;

class UserFileGroup extends AbstractController
{
    /**
     * smart-auto-routing false
     * @return array
     */
    public function post($data = null)
    {
        if(!class_exists($this->entity)) {
            throw new RestException(400, "'$this->entity' entity missing");
        }
        
        if(!isset($_FILES['files']) || !count($_FILES['files'])) {
            throw new RestException(400, "No files to upload");
        }
        $file = UploadManager::getInstance()->fileViaUpload('files', UploadManager::USER_FILE);
        
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

        $result = UploadManager::getInstance()->getResult($collection, UploadManager::USER_FILE);
        
        $conditionsMap = new ConditionsMap();
        $conditionsMap->setIncludeHidden(true);
        $conditionsMap->setController(get_class($this));
        //print_r(Application::getSQLLog());
        return static::resultModefire($result->toArray(), $conditionsMap->getOptionsMap());
    }
    
    /**
     * smart-auto-routing false
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
        
        $file = UploadManager::getInstance()->fileViaUpload('files', UploadManager::USER_FILE);
        
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

        $result = UploadManager::getInstance()->getResult($collection, UploadManager::USER_FILE, intval($id));
        
        $conditionsMap = new ConditionsMap();
        $conditionsMap->setIncludeHidden(true);
        $conditionsMap->setController(get_class($this));
        //print_r(Application::getSQLLog());
        return static::resultModefire($result->toArray(), $conditionsMap->getOptionsMap());
    }
    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\UserFileGroupEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'name' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\UserString',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'inject' => 'text',
                    'foreignkey' => 'name_id'
                 ],
                'user' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\User'
                 ],
                'status' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => [
                        0 => 'Доступен',
                        1 => 'Недоступен'
                    ]],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'choice' => [
                    'validator'=>'Boolean',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'admin_file' => [
                    'validator'=>'Boolean',
                 ],
                'files' => [
                    'relation' => BaseMapper::RELATION_HASMANY,
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'resource' => 'v1\UserFile',
                    'depth' => 2
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

