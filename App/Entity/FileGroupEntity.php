<?php

namespace App\Entity;

class FileGroupEntity extends AbstractEntity {
   
   protected $table = 'filegroup';
   
   public function name()
   {
       return $this->belongsTo('App\Entity\SiteStringEntity', 'name_id');
   }
   
   public function files()
   {
       return $this->hasMany('App\Entity\FileEntity', 'filegroup_id');
   }
}

?>
