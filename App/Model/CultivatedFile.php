<?php

namespace App\Model;

class CultivatedFile {
    
    private $name;
    private $newName;
    private $path;
    private $options;
    
    public function __construct($name, $newName, $path, $options) {
        
        $this->name = $name;
        $this->newName = $newName;
        $this->path = $path;
        $this->options = $options;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getNewName()
    {
        return $this->newName;
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function getNewPath()
    {
        return $this->path.$this->newName;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
}
