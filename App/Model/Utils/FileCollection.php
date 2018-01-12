<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Utils;

class FileCollection {
    
    private $name = '';
    private $files = [];
    
    public function __construct($name, $files = []) {
        $this->name = $name;
        $this->files = $files;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getFiles()
    {
        return $this->files;
    }
    
    public function addFile($file)
    {
        $this->files[] = $file;
    }
}
