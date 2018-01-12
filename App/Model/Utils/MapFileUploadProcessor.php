<?php

namespace App\Model\Utils;

use App\Model\CultivatedFile,
    App\Model\Utils\FileCollection;

class MapFileUploadProcessor {
    
    /**
     * 
     * @param CultivatedFile $file
     * @param array $sizes
     * @return FileCollection
     */
    public static function createMapFile($file)
    {
        return new FileCollection($file->getName(), [
            [
                'dir' => $file->getPath(),
                'path' => $file->getPath().$file->getNewName(),
                'uri' => str_replace(realpath('.'), '', $file->getPath().$file->getNewName()),
                'width' => 0,
                'height' => 0,
                'type' => 0
            ]
        ]);
    }
}
