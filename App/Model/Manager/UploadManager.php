<?php

namespace App\Model\Manager;

use App\Application,
    App\Model\Utils\ImageUploadProcessor,
    Luracast\Restler\RestException,
    App\Entity\FileEntity,
    App\Entity\SiteStringEntity,
    App\Entity\UserFileEntity,
    App\Entity\UserStringEntity,
    App\Entity\FileGroupEntity,
    App\Entity\UserFileGroupEntity,
    App\Entity\UserEntity,
    App\Model\CurrentUser,
    App\Model\Utils\FileCollection,
    App\Model\CultivatedFile;

final class UploadManager implements ManagerInterface{
    
    const SITE_FILE = 'sitefile';
    const USER_FILE = 'userfile';

    /**
     * Singletone instance
     * @var UploadManager 
     */
    private static $instance;
    
    private $initialized = false;
    
    /**
     * 
     * @return UploadManager
     */
    public static function getInstance()
    {
        return static::$instance = static::$instance ?: new static();
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
    
    /**
     * 
     * @param type $aliasKey
     * @param type $context
     * @return boolean|\App\Model\CultivatedFile
     */
    public function fileViaUpload($aliasKey, $context)
    {
        if(!isset($_FILES[$aliasKey])) {
            return false;
        }        
        
        $mimiOptions = $this->getMimiOptions($context == static::SITE_FILE ? Application::getUploadMimi() : Application::getUserUploadMimi(),
                $_FILES[$aliasKey]['type'], $_FILES[$aliasKey]['name']);
        
        if(!$mimiOptions) {
            return false;
        }
        
        $uploadPath = Application::getUploadPath().'/'.($mimiOptions['handling'] ?: '').'/'.($context == static::USER_FILE ? 'user/'.CurrentUser::getId().'/' : '');
        
        if(!file_exists($uploadPath)) {
            if(!mkdir($uploadPath, 0777, true)) {
                return false;
            }
        }
             
        $newName = md5($_FILES[$aliasKey]['name'].'_'.time()).'.'.$mimiOptions['ext'];
        
        move_uploaded_file($_FILES[$aliasKey]['tmp_name'], $uploadPath.$newName);
        
        return new CultivatedFile($_FILES[$aliasKey]['name'], $newName, $uploadPath, $mimiOptions);
    }
    
    /**
     * 
     * @param string $pathToFile
     * @param string $context
     * @return boolean|\App\Model\CultivatedFile
     */
    public function fileViaLocal($pathToFile, $fileName, $context)
    {
        $mimiOptions = $this->getMimiOptions($context == static::SITE_FILE ? Application::getUploadMimi() : Application::getUserUploadMimi(), '', $fileName);
        
        if(!$mimiOptions) {
            return false;
        }
        
        $uploadPath = Application::getUploadPath().'/'.($mimiOptions['handling'] ?: '').'/'.($context == static::USER_FILE ? 'user/'.CurrentUser::getId().'/' : '');
        
        if(!file_exists($uploadPath)) {
            if(!mkdir($uploadPath, 0777, true)) {
                return false;
            }
        }
             
        $newName = md5($fileName.'_'.time()).'.'.$mimiOptions['ext'];
        
        copy($pathToFile.$fileName, $uploadPath.$newName);
        
        return new CultivatedFile($fileName, $newName, $uploadPath, $mimiOptions);
    }
    
    private function getMimiOptions($mimiList = [], $type = false, $name = false)
    {        
        foreach($mimiList as $options) {
            if(($options['type'] && $options['type'] == $type) || ($options['ext'] && preg_match('#.+\.'.$options['ext'].'$#', $name))) {
                return $options;
            }
        }
        return false;
    }
    
    public function getResult()
    {
        @list($collection, $context, $id) = func_get_args();
        
        if($context == static::SITE_FILE) {
            return $this->modifierImage($collection, $id);            
        } elseif($context == static::USER_FILE) {
            return $this->modifierUserImage($collection, $id);
        } else {
            throw new RestException(500, "File uploading unsupported");
        }
    }
    /**
     * 
     * @param FileCollection $collection
     * @param int $id
     * @return FileGroupEntity
     * @throws RestException
     */
    private function modifierImage($collection, $id)
    {
        if($id > 0) {
            $entity = FileGroupEntity::with(['files', 'name'])->find($id);
            
            if(!$entity) {
                throw new RestException(400, "File with id = $id not found!");
            }
        } else {
            $entity = new FileGroupEntity();
        }
        
        $entity->resource_type = 0;
        $files = [];
        
        foreach($collection->getFiles() as $file) {
            $files[] = new FileEntity([
                'mimi_type'=> 0,
                'path' => $file['uri'],
                'width' => $file['width'],
                'height' => $file['height'],
                'size' => $file['type']
            ]);
        }
        
        $fileName = new SiteStringEntity([
                    'resource_type' => 1,
                    'ru' =>$collection->getName(),
                    'en'=>$collection->getName()]);
        
        $fileName->save();
        
        $entity->name()->associate($fileName);
        $entity->save();
        $entity->files()->saveMany($files);
        return $entity;
    }
    
    /**
     * 
     * @param FileCollection $collection
     * @param int $id
     * @return FileGroupEntity
     * @throws RestException
     */    
    private function modifierUserImage($collection, $id)
    {
        if($id > 0) {
            $entity = UserFileGroupEntity::with(['files', 'name'])->find($id);
            
            if(!$entity) {
                throw new RestException(400, "File with id = $id not found!");
            }
        }
        
        $entity = new UserFileGroupEntity();
        $user = UserEntity::find(CurrentUser::getId());
        
        $entity->status = 0;
        $entity->choice = false;
        $entity->admin_file = false;
        
        $files = [];
        foreach($collection->getFiles() as $file) {
            $files[] = new UserFileEntity([
                'mimi_type'=> 0,
                'path' => $file['uri'],
                'width' => $file['width'],
                'height' => $file['height'],
                'size' => $file['type']
            ]);
        }
        $fileName = new UserStringEntity([
                    'resource_type' => 1,
                    'text' => $collection->getName(),
                    'status'=>0]);
        
        $fileName->save();
        
        $entity->user()->associate($user);
        $entity->name()->associate($fileName);
        $entity->save();
        $entity->files()->saveMany($files);
        
        return $entity;
    }
}

?>
