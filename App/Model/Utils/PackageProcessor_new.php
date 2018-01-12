<?php

namespace App\Model\Utils;

use App\Application,
    \SQLite3,
    \ZipArchive,
    Luracast\Restler\RestException,
    App\Entity\AbstractEntity,
    App\Entity\AreaEntity,
    App\Entity\PackageEntity;

class PackageProcessor { 
    
    protected $core = true;
    /**
     *
     * @var \Illuminate\Database\Connection 
     */
    protected $connection = false;
    protected $imageSize = 768;
    protected $userImageSize = 560;
    protected $lockExpired = 1000;


    private $blankDbFile = false;
    private $dumpFile = false;
    private $zipFile = false;
    private $sqlFile = false;
    private $packageSavePath = false;
    private $dumpFileDescription = false;
    /**
     *
     * @var ZipArchive
     */
    private $zipFileDescription = false;
    /**
     *
     * @var SQLite3 
     */
    private $dbFileDescription = false;

    private $areaStack = [];
    private $areaFGStack = [];
    private $landmarkStack = [];
    private $landmarkPropertyStack = [];
    private $peopleStack = [];
    private $featureStack = [];
    private $kitchenStack = [];
    private $fileGroupStack = [];
    private $siteStringkStack = [];
    private $userFileGroupStack = [];
    private $userStringkStack = [];
    
    public function collectPackage(&$package)
    {
        $package->hash = md5($package->id).md5(time());
        
        if(!$package->name) {
            $package->name = md5($package->id.' '. microtime());
        }
        
        $this->areaStack[$package->area_id] = 0;
        $this->core = false;
        
        $this->buildPackage($package);
        return $package;
    }
    
    public function createCorePackage($areaList)
    {
        $package = new PackageEntity([
            'name' => 'core',
            'hash' => md5(mt_srand()).md5(microtime())
        ]);
        
        $this->areaStack = array_flip($areaList);
        
        array_walk($this->areaStack, function(&$item, $key) {
            $item = 0;
        });
        
        $this->core = true;        
        $this->buildPackage($package);
        return $package;
    }
    
    protected function buildPackage($package)
    {
        $this->closeDescriptions();
        
        if(!$this->connection) {
            $this->connection = Application::getCapsule()->getConnection();
        }
        
        if(!$this->blankDbFile) {
            $this->blankDbFile = Application::getDataPath().'/blank.db';
        }
        
        if(!$this->packageSavePath) {
            $this->packageSavePath = Application::getUploadPath().'/packages';
            $this->dumpFile = $this->packageSavePath.'/'.$package->name.'.sql';
            $this->sqlFile = $this->packageSavePath.'/'.$package->name.'.db';
            $this->zipFile = $this->packageSavePath.'/'.$package->name.'.zip';
        }
        
        if($this->isLocked()) {
            throw new RestException(423, 'Процесс формирования предидущего пакета, ещё не завершён!');
        } else {
            $this->lock();
        }
        
        $this->dumpFileDescription = fopen($this->dumpFile, 'w');
        $this->runAreaStack();
        
        if(file_exists($this->zipFile)) {
            unlink($this->zipFile);
        }
        
        $this->zipFileDescription = new ZipArchive();
        $this->zipFileDescription->open($this->zipFile, ZIPARCHIVE::CREATE);
        $this->runFileGroupStack();
        $this->runSiteStringStack();
        $this->runUserFileGroupStack();
        $this->runUserStringStack();     
        
        copy($this->blankDbFile, $this->sqlFile);
        
        if($this->dumpFileDescription) {
            fclose($this->dumpFileDescription);
            $this->dumpFileDescription = false;
        }
        
        $this->dbFileDescription = new SQLite3($this->sqlFile);
        //$this->dbFileDescription->busyTimeout(5000);
        $this->dbFileDescription->exec('PRAGMA journal_mode=OFF;');
        
        set_time_limit(0);
        
        if($this->dbFileDescription->exec(file_get_contents($this->dumpFile)) === false) {
            $code = $this->dbFileDescription->lastErrorCode();
            $message = $this->dbFileDescription->lastErrorMsg();
            $this->closeDescriptions();
            throw new RestException(500, $code.': '.$message);
        }
        
        $this->zipFileDescription->addFile($this->sqlFile, $package->name.'.db');
        $this->closeDescriptions();
        
        $package->size = filesize($this->zipFile);
        $package->path = str_replace(realpath('.'), '', $this->zipFile);
    }
    
    protected function runSiteStringStack()
    {
        if(count($this->siteStringkStack) < 1) {
            return;
        }
        
        foreach($this->connection->select('select * from sitestring as s where s.id in ('.implode(',', array_keys($this->siteStringkStack)).')') as $item) {
            $pd = [
                '"'.$item['id'].'"',
                '"'.str_replace('"', "'", htmlspecialchars_decode($item['ru'])).'"',
                '""',//Английская версия может подаждёт
                '"'.(($crt = strtotime($item['created_at'])) !== false ? $crt : time()).'"',
                '"'.(($crt = strtotime($item['updated_at'])) !== false ? $crt : time()).'"'
            ];
            
            fputs($this->dumpFileDescription, 'insert or replace into `sitestring` values ('.implode(', ', $pd).");\r\n");
        }
        
        fflush($this->dumpFileDescription);
        $this->siteStringkStack = [];
    }
    
    protected function runUserStringStack()
    {
        if(count($this->userStringkStack) < 1) {
            return;
        }
        
        foreach($this->connection->select('select * from userstring as us where us.id in ('.implode(',', array_keys($this->userStringkStack)).')') as $item) {
            $pd = [
                '"'.$item['id'].'"',
                '"'.str_replace('"', "'", htmlspecialchars_decode($item['text'])).'"',
                '"'.(($crt = strtotime($item['created_at'])) !== false ? $crt : time()).'"',
                '"'.(($crt = strtotime($item['updated_at'])) !== false ? $crt : time()).'"'
            ];
            
            fputs($this->dumpFileDescription, 'insert or replace into `userstring` values ('.implode(', ', $pd).");\r\n");
        }
        
        fflush($this->dumpFileDescription);
        $this->userStringkStack = [];
    }
    
    protected function runFileGroupStack()
    {
        if(count($this->fileGroupStack) < 1) {
            return;
        }
        
        $im = implode(',', array_keys($this->fileGroupStack));
        
        foreach($this->connection->select('select * from filegroup as fg where fg.id in ('.$im.')') as $item) {
            $pd = [
                '"'.$item['id'].'"',
                '"'.$item['name_id'].'"',
                '"'.(($crt = strtotime($item['created_at'])) !== false ? $crt : time()).'"',
                '"'.(($crt = strtotime($item['updated_at'])) !== false ? $crt : time()).'"'
            ];           
            
            $this->siteStringkStack[$item['name_id']] = 0;
            
            fputs($this->dumpFileDescription, 'insert or replace into `filegroup` values ('.implode(', ', $pd).");\r\n");
        }
        fflush($this->dumpFileDescription);
        
        
        foreach($this->connection->select('select * from file as f where f.filegroup_id in ('.$im.')') as $item) {
            
            if(!isset($this->fileGroupStack[$item['filegroup_id']])) {
                continue;
            }
            
            if(!is_array($this->fileGroupStack[$item['filegroup_id']])) {
                $this->fileGroupStack[$item['filegroup_id']] = [];
            }
            
            $this->fileGroupStack[$item['filegroup_id']][$item['id']] = $item;
        }
        
        foreach($this->fileGroupStack as $filegroup=>$files) {
            if(!is_array($files)) {
                continue;
            }
            $selected = false;
            foreach($files as $file) {
                
                $file['width'] = intval($file['width']);
                
                if($file['width'] <= $this->imageSize) {
                    if($selected && $file['width'] < $selected['width']) {
                        continue;
                    }
                    $selected = $file;
                }
            }
            
            if(!$selected) {
                $selected = array_shift($files);
            }
            
            if(!$selected) {
                continue;
            }
            
            $p = [
                '"'.$selected['id'].'"',
                '"'.$selected['filegroup_id'].'"',
                '"'.$selected['mimi_type'].'"',
                '"'.$selected['path'].'"',
                '"'.$selected['width'].'"',
                '"'.$selected['height'].'"',
                '"'.$selected['size'].'"',
                '"'.(($crt = strtotime($selected['created_at'])) !== false ? $crt : time()).'"',
                '"'.(($crt = strtotime($selected['updated_at'])) !== false ? $crt : time()).'"'
            ];
            
            if(file_exists(realpath('.').$selected['path']) && $this->zipFileDescription) {
                $this->zipFileDescription->addFile(realpath('.').$selected['path'], substr($selected['path'], 1));             
            }
            
            fputs($this->dumpFileDescription, 'insert or replace into `file` values ('.implode(', ', $p).");\r\n");
        }
        
        fflush($this->dumpFileDescription);        
        $this->fileGroupStack = [];
    }
    
    protected function runUserFileGroupStack()
    {
        if(count($this->userFileGroupStack) < 1) {
            return;
        }
        
        $im = implode(',', array_keys($this->userFileGroupStack));
        
        foreach($this->connection->select('select * from userfilegroup as ufg where ufg.id in ('.$im.') and ufg.status > 0') as $item) {
            $pd = [
                '"'.$item['id'].'"',
                '"'.$item['name_id'].'"',
                '"'.$item['user_id'].'"',
                '"'.(($crt = strtotime($item['created_at'])) !== false ? $crt : time()).'"',
                '"'.(($crt = strtotime($item['updated_at'])) !== false ? $crt : time()).'"'
            ];           
            
            $this->userStringkStack[$item['name_id']] = 0;
            
            fputs($this->dumpFileDescription, 'insert or replace into `userfilegroup` values ('.implode(', ', $pd).");\r\n");
        }
        fflush($this->dumpFileDescription);
        
        
        foreach($this->connection->select('select * from userfile as uf where uf.userfilegroup_id in ('.$im.')') as $item) {
            
            if(!isset($this->userFileGroupStack[$item['userfilegroup_id']])) {
                continue;
            }
            
            if(!is_array($this->userFileGroupStack[$item['userfilegroup_id']])) {
                $this->userFileGroupStack[$item['userfilegroup_id']] = [];
            }
            
            $this->userFileGroupStack[$item['userfilegroup_id']][$item['id']] = $item;
        }
        
        foreach($this->userFileGroupStack as $filegroup=>$files) {
            if(!is_array($files)) {
                continue;
            }
            $selected = false;
            foreach($files as $file) {
                
                $file['width'] = intval($file['width']);
                
                if($file['width'] <= $this->userImageSize) {
                    if($selected && $file['width'] < $selected['width']) {
                        continue;
                    }
                    $selected = $file;
                }
            }
            
            if(!$selected) {
                $selected = array_shift($files);
            }
            
            if(!$selected) {
                continue;
            }
            
            $p = [
                '"'.$selected['id'].'"',
                '"'.$selected['userfilegroup_id'].'"',
                '"'.$selected['mimi_type'].'"',
                '"'.$selected['path'].'"',
                '"'.$selected['width'].'"',
                '"'.$selected['height'].'"',
                '"'.$selected['size'].'"',
                '"'.(($crt = strtotime($selected['created_at'])) !== false ? $crt : time()).'"',
                '"'.(($crt = strtotime($selected['updated_at'])) !== false ? $crt : time()).'"'
            ];
            
            if(file_exists(realpath('.').$selected['path']) && $this->zipFileDescription) {
                $this->zipFileDescription->addFile(realpath('.').$selected['path'], substr($selected['path'], 1));             
            }
            
            fputs($this->dumpFileDescription, 'insert or replace into `userfile` values ('.implode(', ', $p).");\r\n");
        }
        
        fflush($this->dumpFileDescription);        
        $this->userFileGroupStack = [];
    }
    
    protected function runAreaStack()
    {
        if(count($this->areaStack) < 1) {
            return;
        }
        
        $upperStack = $lowerStack = [];
                
        foreach($this->connection->select('select * from area as a where a.id in ('.implode(',', array_keys($this->areaStack)).') and a.status > 0') as $item) {
            
            if(!isset($this->areaStack[$item['id']])) {
                continue;
            }
            
            $depth =  $this->areaStack[$item['id']];
            
            $p = [
                '"'.$item['id'].'"',
                '"'.$item['name_id'].'"',
                '"'.$item['alias'].'"',
                '"'.$item['area_type'].'"',
                '"'.$item['detail_id'].'"',
                '"'.$item['transport_text_id'].'"',
                '"'.$item['history_text_id'].'"',
                '"'.$item['landmark_text_id'].'"',
                '"'.$item['kitchen_text_id'].'"',
                '"'.$item['people_text_id'].'"',
                '"'.$item['event_text_id'].'"',
                '"'.$item['rating'].'"',
                '"'.$item['latitude'].'"',
                '"'.$item['longitude'].'"',
                '"'.$item['popular'].'"',
                '"'.$item['map_id'].'"',
                '"'.$item['flag_id'].'"',
                '"'.$item['image_id'].'"',
                '"'.$item['mobile_image_id'].'"',
                '"'.$item['mobile_map_file_id'].'"',
                '"'.$item['empty_image_id'].'"',
                '"'.(($crt = strtotime($item['created_at'])) !== false ? $crt : time()).'"',
                '"'.(($crt = strtotime($item['updated_at'])) !== false ? $crt : time()).'"'
            ];
            
            if($depth <= 0) {
                
                $lowerStack[] = $item['id'];
                
                if(!$this->core || ($this->core && $depth == 0)) {
                    $this->siteStringkStack[$item['name_id']] = 0;
                    $this->siteStringkStack[$item['detail_id']] = 0;
                    if(!$this->core) {
                        $this->siteStringkStack[$item['transport_text_id']] = 0;
                        $this->siteStringkStack[$item['history_text_id']] = 0;
                        $this->siteStringkStack[$item['landmark_text_id']] = 0;
                        $this->siteStringkStack[$item['kitchen_text_id']] = 0;
                        $this->siteStringkStack[$item['people_text_id']] = 0;
                        $this->siteStringkStack[$item['event_text_id']] = 0;
                    }
                    $this->fileGroupStack[$item['map_id']] = 0;
                    $this->fileGroupStack[$item['flag_id']] = 0;
                    $this->fileGroupStack[$item['image_id']] = 0;
                    $this->fileGroupStack[$item['mobile_image_id']] = 0;
                    //$this->fileGroupStack[$item['mobile_map_file_id']] = 0;
                    $this->fileGroupStack[$item['empty_image_id']] = 0;

                    fputs($this->dumpFileDescription, 'insert or replace into `area` values ('.implode(', ', $p).");\r\n");
                }
            }
            
            if($depth >= 0) {
                $upperStack[] = $item['id'];
            }
        }
        
        $this->areaStack = [];  
        
        if(count($upperStack) > 0) {
            if(!$this->core) { // это приведёт к мегаувеличению пакета города
                $this->areaFGStack = array_merge($this->areaFGStack, $upperStack);
            }
            foreach($this->connection->select('select * from arealinks as al where al.child_id in ('.implode(',', $upperStack).')') as $item) {
                $this->areaStack[$item['parent_id']] = 1;
                $item['created_at'] = ($crt = strtotime($item['created_at'])) !== false ? $crt : time();
                $item['updated_at'] = ($crt = strtotime($item['updated_at'])) !== false ? $crt : time();
                fputs($this->dumpFileDescription, "insert or replace into `arealinks` values ('{$item['parent_id']}', '{$item['child_id']}', '{$item['capital']}', '{$item['created_at']}', '{$item['updated_at']}');\r\n");
            }
        }
        
        if(count($lowerStack) > 0) {
            if(!$this->core) {
                $this->areaFGStack = array_merge($this->areaFGStack, $lowerStack);
            }
            foreach($this->connection->select('select * from arealinks as al where al.parent_id in ('.implode(',', $lowerStack).')') as $item) {
                $this->areaStack[$item['child_id']] = -1;
                $item['created_at'] = ($crt = strtotime($item['created_at'])) !== false ? $crt : time();
                $item['updated_at'] = ($crt = strtotime($item['updated_at'])) !== false ? $crt : time();
                fputs($this->dumpFileDescription, "insert or replace into `arealinks` values ('{$item['parent_id']}', '{$item['child_id']}', '{$item['capital']}', '{$item['created_at']}', '{$item['updated_at']}');\r\n");
            }
        }
        
        fflush($this->dumpFileDescription);
        
        if(count($this->areaStack) > 0) {
            $this->runAreaStack();
        } else {
            $this->runAreaFGStack();
        }
    }
    
    protected function runAreaFGStack()
    {
        if(count($this->areaFGStack) < 1) {
            return;
        }
        
        $areaFGStack = array_unique($this->areaFGStack);
        $im = implode(',', $areaFGStack);

        foreach($this->connection->select('select * from kitchen_area as ka where ka.area_id in ('.$im.')') as $item) {
            $this->kitchenStack[$item['kitchen_id']] = 0;
            $item['created_at'] = ($crt = strtotime($item['created_at'])) !== false ? $crt : time();
            $item['updated_at'] = ($crt = strtotime($item['updated_at'])) !== false ? $crt : time();
            fputs($this->dumpFileDescription, "insert or replace into `kitchen_area` values ('{$item['kitchen_id']}', '{$item['area_id']}', '{$item['system']}', '{$item['created_at']}', '{$item['updated_at']}');\r\n");
        }
        $this->runKitchenStack();
        
        foreach($this->connection->select('select * from people_area as pa where pa.area_id in ('.$im.')') as $item) {
            $this->peopleStack[$item['people_id']] = 0;
            $item['created_at'] = ($crt = strtotime($item['created_at'])) !== false ? $crt : time();
            $item['updated_at'] = ($crt = strtotime($item['updated_at'])) !== false ? $crt : time();
            fputs($this->dumpFileDescription, "insert or replace into `people_area` values ('{$item['area_id']}', '{$item['people_id']}', '{$item['system']}', '{$item['created_at']}', '{$item['updated_at']}');\r\n");
        }        
        $this->runPeopleStack();
        
        foreach($this->connection->select('select * from feature_area as fa where fa.area_id in ('.$im.')') as $item) {
            $this->featureStack[$item['feature_id']] = 0;
            $item['created_at'] = ($crt = strtotime($item['created_at'])) !== false ? $crt : time();
            $item['updated_at'] = ($crt = strtotime($item['updated_at'])) !== false ? $crt : time();
            fputs($this->dumpFileDescription, "insert or replace into `feature_area` values ('{$item['feature_id']}', '{$item['area_id']}', '{$item['system']}', '{$item['created_at']}', '{$item['updated_at']}');\r\n");
        }        
        $this->runFeatureStack();
        
        foreach($this->connection->select('select * from landmark_area as la where la.area_id in ('.$im.')') as $item) {
            $this->landmarkStack[$item['landmark_id']] = 0;
            $item['created_at'] = ($crt = strtotime($item['created_at'])) !== false ? $crt : time();
            $item['updated_at'] = ($crt = strtotime($item['updated_at'])) !== false ? $crt : time();
            fputs($this->dumpFileDescription, "insert or replace into `landmark_area` values ('{$item['landmark_id']}', '{$item['area_id']}', '{$item['vicinity']}', '{$item['distance']}', '{$item['system']}', '{$item['created_at']}', '{$item['updated_at']}');\r\n");
        }
        $this->runLandmarkStack();
        
        fflush($this->dumpFileDescription);
        $this->areaFGStack = [];
    }
    
    protected function runLandmarkStack()
    {
        if(count($this->landmarkStack) < 1) {
            return;
        }
        $im = implode(',', array_keys($this->landmarkStack));
        foreach($this->connection->select('select * from landmark as l where l.id in ('.$im.') and l.status > 0') as $item) {
            $pd = [
                '"'.$item['id'].'"',
                '"'.$item['name_id'].'"',
                '"'.$item['original_name_id'].'"',
                '"'.$item['text_id'].'"',
                '"'.$item['full_text_id'].'"',
                '"'.$item['alias'].'"',
                '"'.$item['rating'].'"',
                '"'.(($crt = strtotime($item['build'])) !== false ? $crt : time()).'"',
                '"'.$item['build_year'].'"',
                '"'.$item['build_abt'].'"',
                '"'.$item['adress'].'"',
                '"'.$item['latitude'].'"',
                '"'.$item['longitude'].'"',
                '"'.$item['regional'].'"',
                '"'.$item['image_id'].'"',
                '"'.(($crt = strtotime($item['created_at'])) !== false ? $crt : time()).'"',
                '"'.(($crt = strtotime($item['updated_at'])) !== false ? $crt : time()).'"'
            ];          
            
            $this->siteStringkStack[$item['name_id']] = 0;
            $this->siteStringkStack[$item['original_name_id']] = 0;
            $this->siteStringkStack[$item['text_id']] = 0;
            $this->siteStringkStack[$item['full_text_id']] = 0;

            $this->fileGroupStack[$item['image_id']] = 0;
            
            fputs($this->dumpFileDescription, 'insert or replace into `landmark` values ('.implode(', ', $pd).");\r\n");
        }
        
        foreach($this->connection->select('select * from landmark_landmarkproperty as llp where llp.landmark_id in ('.$im.')') as $item) {
            $this->landmarkPropertyStack[$item['landmarkproperty_id']] = 0;
            $item['created_at'] = ($crt = strtotime($item['created_at'])) !== false ? $crt : time();
            $item['updated_at'] = ($crt = strtotime($item['updated_at'])) !== false ? $crt : time();
            fputs($this->dumpFileDescription, "insert or replace into `landmark_landmarkproperty` values ('{$item['landmarkproperty_id']}', '{$item['landmark_id']}', '{$item['created_at']}', '{$item['updated_at']}');\r\n");
        }
        
        $this->runLandmarkPropertyStack();
        
        foreach($this->connection->select('select * from landmark_gallery as lg where lg.landmark_id in ('.$im.')') as $item) {
            $this->userFileGroupStack[$item['user_file_id']] = 0;
            $item['created_at'] = ($crt = strtotime($item['created_at'])) !== false ? $crt : time();
            $item['updated_at'] = ($crt = strtotime($item['updated_at'])) !== false ? $crt : time();
            fputs($this->dumpFileDescription, "insert or replace into `landmark_gallery` values ('{$item['landmark_id']}', '{$item['user_file_id']}', '{$item['created_at']}', '{$item['updated_at']}');\r\n");
        }
        
        fflush($this->dumpFileDescription);
        $this->landmarkStack = [];
    }
    
    protected function runLandmarkPropertyStack()
    {
        if(count($this->landmarkPropertyStack) < 1) {
            return;
        }
        
        $im = implode(',', array_keys($this->landmarkPropertyStack));
        foreach($this->connection->select('select * from landmarkproperty as lp where lp.id in ('.$im.')') as $item) {
            $pd = [
                '"'.$item['id'].'"',
                '"'.$item['name_id'].'"',
                '"'.$item['icon_id'].'"',
                '"'.(($crt = strtotime($item['created_at'])) !== false ? $crt : time()).'"',
                '"'.(($crt = strtotime($item['updated_at'])) !== false ? $crt : time()).'"'
            ];           
            
            $this->siteStringkStack[$item['name_id']] = 0;
            $this->fileGroupStack[$item['icon_id']] = 0;
            
            fputs($this->dumpFileDescription, 'insert or replace into `landmarkproperty` values ('.implode(', ', $pd).");\r\n");
        }
        $this->landmarkPropertyStack = [];
        
        foreach($this->connection->select('select * from landmarkpropertylinks as lpl where lpl.child_id in ('.$im.')') as $item) {
            $this->landmarkPropertyStack[$item['parent_id']] = 0;
            $item['created_at'] = ($crt = strtotime($item['created_at'])) !== false ? $crt : time();
            $item['updated_at'] = ($crt = strtotime($item['updated_at'])) !== false ? $crt : time();
            fputs($this->dumpFileDescription, "insert or replace into `landmarkpropertylinks` values ('{$item['parent_id']}', '{$item['child_id']}', '{$item['created_at']}', '{$item['updated_at']}');\r\n");
        }
        
        fflush($this->dumpFileDescription);        
        if(count($this->landmarkPropertyStack) > 0) {
            $this->runLandmarkPropertyStack();
        }
    }
    
    protected function runKitchenStack()
    {
        if(count($this->kitchenStack) < 1) {
            return;
        }
        
        foreach($this->connection->select('select * from kitchen as k where k.id in ('.implode(',', array_keys($this->kitchenStack)).') and k.status > 0') as $item) {
            $pd = [
                '"'.$item['id'].'"',
                '"'.$item['alias'].'"',
                '"'.$item['type'].'"',
                '"'.$item['name_id'].'"',
                '"'.$item['original_name_id'].'"',
                '"'.$item['text_id'].'"',
                '"'.$item['image_id'].'"',
                '"'.(($crt = strtotime($item['created_at'])) !== false ? $crt : time()).'"',
                '"'.(($crt = strtotime($item['updated_at'])) !== false ? $crt : time()).'"'
            ];           
            
            $this->siteStringkStack[$item['name_id']] = 0;
            $this->siteStringkStack[$item['original_name_id']] = 0;
            $this->siteStringkStack[$item['text_id']] = 0;

            $this->fileGroupStack[$item['image_id']] = 0;
            
            fputs($this->dumpFileDescription, 'insert or replace into `kitchen` values ('.implode(', ', $pd).");\r\n");
        }
        
        fflush($this->dumpFileDescription);
        $this->kitchenStack = [];
    }
    
    protected function runFeatureStack()
    {
        if(count($this->featureStack) < 1) {
            return;
        }
        
        foreach($this->connection->select('select * from feature as f where f.id in ('.implode(',', array_keys($this->featureStack)).') and f.status > 0') as $item) {
            $pd = [
                '"'.$item['id'].'"',
                '"'.$item['alias'].'"',
                '"'.$item['name_id'].'"',
                '"'.$item['original_name_id'].'"',
                '"'.$item['text_id'].'"',
                '"'.$item['image_id'].'"',
                '"'.(($crt = strtotime($item['created_at'])) !== false ? $crt : time()).'"',
                '"'.(($crt = strtotime($item['updated_at'])) !== false ? $crt : time()).'"'
            ];            
            
            $this->siteStringkStack[$item['name_id']] = 0;
            $this->siteStringkStack[$item['original_name_id']] = 0;
            $this->siteStringkStack[$item['text_id']] = 0;

            $this->fileGroupStack[$item['image_id']] = 0;
            
            fputs($this->dumpFileDescription, 'insert or replace into `feature` values ('.implode(', ', $pd).");\r\n");
        }
        
        fflush($this->dumpFileDescription);
        $this->featureStack = [];
    }
    
    protected function runPeopleStack()
    {
        if(count($this->peopleStack) < 1) {
            return;
        }
        
        foreach($this->connection->select('select * from people as p where p.id in ('.implode(',', array_keys($this->peopleStack)).') and p.status > 0') as $item) {
            $pd = [
                '"'.$item['id'].'"',
                '"'.$item['name_id'].'"',
                '"'.$item['original_name_id'].'"',
                '"'.$item['alias'].'"',
                '"'.(($crt = strtotime($item['born'])) !== false ? $crt : time()).'"',
                '"'.$item['born_year'].'"',
                '"'.$item['born_abt'].'"',
                '"'.(($crt = strtotime($item['death'])) !== false ? $crt : time()).'"',
                '"'.$item['death_year'].'"',
                '"'.$item['death_abt'].'"',
                '"'.$item['alive'].'"',
                '"'.$item['detail_id'].'"',
                '"'.$item['image_id'].'"',
                '"'.(($crt = strtotime($item['created_at'])) !== false ? $crt : time()).'"',
                '"'.(($crt = strtotime($item['updated_at'])) !== false ? $crt : time()).'"'
            ];
            
            
            $this->siteStringkStack[$item['name_id']] = 0;
            $this->siteStringkStack[$item['original_name_id']] = 0;
            $this->siteStringkStack[$item['detail_id']] = 0;

            $this->fileGroupStack[$item['image_id']] = 0;
            
            fputs($this->dumpFileDescription, 'insert or replace into `people` values ('.implode(', ', $pd).");\r\n");
        }
        
        fflush($this->dumpFileDescription);
        $this->peopleStack = [];
    }
    
    protected function closeDescriptions()
    {
        if($this->dumpFileDescription) {
            fclose($this->dumpFileDescription);
            $this->dumpFileDescription = false;
        }
        
        if($this->zipFileDescription) {
            $this->zipFileDescription->close();
            $this->zipFileDescription = false;
        }
        
        if($this->dbFileDescription) {
            $this->dbFileDescription->close();
            $this->dbFileDescription = false;
        }
        
        $this->unlock();
    }
    
    protected function isLocked()
    {
        $lockFile = Application::getUploadPath().'/packages/.lock';
        if(file_exists($lockFile)) {
            return time() <= intval(file_get_contents($lockFile));
        }
        return false;
    }
    
    protected function unlock()
    {
        $lockFile = Application::getUploadPath().'/packages/.lock';
        if(file_exists($lockFile)) {
            return unlink($lockFile);
        }
        
        return true;
    }
    
    protected function lock()
    {
        return file_put_contents(Application::getUploadPath().'/packages/.lock', time() + $this->lockExpired) !== false ?: false;
    }
}
