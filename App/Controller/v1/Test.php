<?php
namespace v1;

use App\Application,
    App\Controller\AbstractController,
    App\Entity\LandmarkEntity,
    App\Model\Manager\UploadManager,
    App\Model\Utils\ImageUploadProcessor,
    App\Entity\SiteStringEntity;

class Test extends AbstractController
{
    /**
     * @param int $step {@from query}
     * @param int $page {@from query}
     * @param int $pagesize {@from query}
     * @return array
     */
    public function one($step = null, $page = null, $pagesize = null)
    {
        $step = intval($step) > 0 ? intval($step) : 0;
        $page = intval($page) > 0 ? intval($page) : 1;
        $pageSize = intval($pagesize) > 0 ? intval($pagesize) : 5;
        
        ignore_user_abort(1);
        set_time_limit(0);
        /*if($page > 10) {
            $step++;
            $page = 0;
        }*/
        
        switch($step) {
            case 0:
                $items = LandmarkEntity::skip(($page - 1) * $pageSize)->take($pageSize)->get();
                $path = realpath('.').'/upload/tmp/';
                $notfound = realpath('.').'/upload/tmp/1notfound.log';
                if(count($items) < 1) {
                    $step++;
                    $page = 0;
                } else {
                    $page++;
                    
                    $f = fopen($notfound, 'a');
                    foreach($items as $item) {
                        if($item['tmp_img1_src']) {
                            if(!file_exists($path.$item['tmp_img1_src'])) {
                                $file = file_get_contents('http://www.cult-turist.ru/img/'.$item['tmp_img1_src']);
                                if($file === false) {
                                    fputs($f, "\r\n".$item['id']);
                                } else {
                                    if(file_put_contents($path.$item['tmp_img1_src'], $file) === false) {
                                        fputs($f, "\r\n".$item['id']);
                                    }                              
                                }
                            }
                        }
                        if($item['tmp_img2_src']) {
                            if(!file_exists($path.$item['tmp_img2_src'])) {
                                $file = file_get_contents('http://www.cult-turist.ru/img/'.$item['tmp_img2_src']);
                                if($file === false) {
                                    fputs($f, "\r\n".$item['id']);
                                } else {
                                    if(file_put_contents($path.$item['tmp_img2_src'], $file) === false) {
                                        fputs($f, "\r\n".$item['id']);
                                    }                         
                                }
                            }
                        }
                    }
                    fclose($f);
                }
                break;
            case 1:
                $items = LandmarkEntity::skip(($page - 1) * $pageSize)->take($pageSize)->get();
                $path = realpath('.').'/upload/tmp/';
                
                if(count($items) < 1) {
                    $step++;
                    $page = 0;
                } else {
                    $page++;
                    foreach($items as $item) {
                        $fs = false;
                        if($item['tmp_img1_src']) {
                            $fs = $item['tmp_img1_src'];
                        } else if($item['tmp_img2_src']) {
                            $fs = $item['tmp_img2_src'];
                        }
                        if($fs) {
                            if(file_exists($path.$fs)) {
                                $file = UploadManager::getInstance()->fileViaLocal($path, $fs, UploadManager::SITE_FILE);
                                
                                if($file) {
                                    if($file->getOptions()['handling'] == 'images') {
                                        $collection = ImageUploadProcessor::createImagesBySizes($file, Application::getImageUploadSizes());
                                        $newFile = UploadManager::getInstance()->getResult($collection, UploadManager::SITE_FILE);
                                        $item->image()->associate($newFile);
                                        $item->save();
                                    }
                                }
                            }
                        }
                        
                        if($fs === $item['tmp_img1_src'] && $item['tmp_img2_src']) {
                            $fs = $item['tmp_img2_src'];
                            if(file_exists($path.$fs)) {
                                
                                $file = UploadManager::getInstance()->fileViaLocal($path, $fs, UploadManager::USER_FILE);
                                
                                if($file) {
                                    if($file->getOptions()['handling'] == 'images') {
                                        $collection = ImageUploadProcessor::createImagesBySizes($file, Application::getImageUploadSizes());
                                        $newFile = UploadManager::getInstance()->getResult($collection, UploadManager::USER_FILE);
                                        $item->gallery()->attach($newFile->id);
                                        $item->save();
                                    }
                                }
                            }
                        }
                    }
                }
                break;
        }
        
        if($step < 2) {
            echo "<script language = 'javascript'>";
            echo "document.location.href='http://{$_SERVER["HTTP_HOST"]}/api/test/one/?page={$page}&step={$step}&pagesize={$pageSize}';";
            echo '</script>';
        }
        exit;
    }
    
    /**
     * 
     * @return array
     */
    public function char()
    {
        $lis = LandmarkEntity::with('name')->whereHas('name', function($query) {
            $query->where('ru', 'like', '%&%');
        })->get();
        
        $q = '';
        
        foreach($lis as $item) {
            $q .= "update `sitestring` set `ru` = \"".html_entity_decode($item->name->ru, ENT_NOQUOTES)."\" where `id` = ".$item->name->id.";\r\n";
        }
        //print_r($q);
        file_put_contents(Application::getUploadPath().'/packages/ds.sql', $q);
                
        return $lis;
    }
}

