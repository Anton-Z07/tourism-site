<?php

namespace App\Model\Utils;

use App\Application,
    App\Model\CultivatedFile,
    App\Model\Utils\FileCollection,
    Imagick;

class ImageUploadProcessor {
    
    const SCALE_WIDTH = 'width';
    const SCALE_HEIGHT = 'height';
    const SCALE_BOTH = 'both';
    
    const POSITION_CENTER = 'center';
    const POSITION_TOP = 'top';
    const POSITION_TOPRIGHT = 'topright';
    const POSITION_RIGHT = 'right';
    const POSITION_RIGHTBOTTOM = 'rightbottom';
    const POSITION_BOTTOM = 'bottom';
    const POSITION_BOTTOMLEFT = 'bottomleft';
    const POSITION_LEFT = 'left';
    const POSITION_TOPLEFT = 'topleft';
    
    private static $filter = Imagick::FILTER_LANCZOS;

    /**
     * 
     * @param CultivatedFile $file
     * @param array $sizes
     * @return FileCollection
     */
    public static function createImagesBySizes($file, $sizes)
    {
        $source = new Imagick($file->getNewPath());
        $sourceWidth = $source->getimagewidth();
        $sourceHeight = $source->getimageheight();
        
        $result = new FileCollection($file->getName());
        
        foreach($sizes as $type => $options) {
            
            $options = array_merge(['width' => 0, 'height' => 0, 'position' => false], $options);
            
            if(isset($options['height']) && $options['height'] > $sourceHeight) {
                continue;
            }
            
            if(isset($options['width']) && $options['width'] > $sourceWidth) {
                continue;
            }
            
            $readyFile = [
                'dir' => $file->getPath().$options['type'],
                'path' => $file->getPath().$options['type'].'/'.$file->getNewName(),
                'uri' => str_replace(realpath('.'), '', $file->getPath().$options['type'].'/'.$file->getNewName()),
                'width' => 0,
                'height' => 0,
                'type' => $type
            ];
            
            $img = static::thumbnailImage($source->getimage(), $sourceWidth, $sourceHeight, $options['width'], $options['height'], $options['position'] ? $options['position'] : static::POSITION_CENTER);
            
            $readyFile['width'] = $img->getimagewidth();
            $readyFile['height'] = $img->getimageheight();
            
            if(!file_exists($readyFile['dir'])) {
                mkdir($readyFile['dir']);
            }
            
            $img->stripImage();
            $img->writeImage($readyFile['path']);
            $img->clear();
            $img->destroy();
            
            $result->addFile($readyFile);
        }
        $source->clear();
        $source->destroy();
        unlink($file->getNewPath());
        
        return $result;
    }
    
    /**
     * 
     * @param Imagick $image
     * @param int $sourceWidth
     * @param int $sourceHeight
     * @param int $toWidth
     * @param int $toHeight
     * @return Imagick
     */
    private static function thumbnailImage($image, $sourceWidth, $sourceHeight, $toWidth = 0, $toHeight = 0, $position = false)
    {
        if($toWidth === 0 && $toHeight === 0) {
            return $image;
        } elseif($toWidth === 0 && $toHeight > 0) {
            $scaleHeight = floor(100 * $toHeight / $sourceHeight) / 100;
            if($scaleHeight  !== 1) {
                $image->resizeimage(0, $toHeight, static::$filter, true);
            }
            return $image;
            
        } elseif($toWidth > 0 && $toHeight === 0) {
            $scaleWidth = floor(100 * $toWidth / $sourceWidth) / 100;
            if($scaleWidth  !== 1) {
                $image->resizeimage($toWidth, 0, static::$filter, true);
            }
            return $image;
        } elseif($toWidth > 0 && $toHeight > 0) {
            $scaleHeight = floor(100 * $toHeight / $sourceHeight) / 100;
            $scaleWidth = floor(100 * $toWidth / $sourceWidth) / 100;
            
            if($scaleHeight === $scaleWidth) {
                $image->resizeimage($toWidth, $toHeight, static::$filter, true);
                return $image;
            } elseif($scaleHeight > $scaleWidth) {
                $image->resizeimage(0, $toHeight, static::$filter, true);
            } elseif($scaleHeight < $scaleWidth) {
                $image->resizeimage($toWidth, 0, static::$filter, true);
            }
            
            $cropX = $cropY = 0;
            
            switch($position) {
                case static::POSITION_TOP:
                case static::POSITION_BOTTOM:
                case static::POSITION_CENTER:
                    $cropX = $image->getimagewidth() > $toWidth ? round($image->getimagewidth() - $toWidth) / 2 : 0;
                    break;
                case static::POSITION_TOPRIGHT:
                case static::POSITION_RIGHT:
                case static::POSITION_RIGHTBOTTOM:
                    $cropX = $image->getimagewidth() > $toWidth ? $image->getimagewidth() - $toWidth : 0;
                    break;
            }
            
            switch($position) {
                case static::POSITION_RIGHT:
                case static::POSITION_LEFT:
                case static::POSITION_CENTER:
                    $cropY = $image->getimageheight() > $toHeight ? round($image->getimageheight() - $toHeight) / 2 : 0;
                    break;
                case static::POSITION_BOTTOMLEFT:
                case static::POSITION_RIGHTBOTTOM:
                case static::POSITION_BOTTOM:
                    $cropY = $image->getimageheight() > $toHeight ? $image->getimageheight() - $toHeight : 0;
                    break;
            }
            //echo $toWidth." ".$toHeight." ".$cropX." ".$cropY." ".$image->getimagewidth()." ".$image->getimageheight();
            $image->cropImage($toWidth, $toHeight, $cropX,  $cropY);
            //$image->cropthumbnailimage($toWidth, $toHeight);
             
            return $image;
        }
    }
}
