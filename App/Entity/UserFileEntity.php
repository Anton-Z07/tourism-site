<?php

namespace App\Entity;

class UserFileEntity extends AbstractEntity {
   
   protected $table = 'userfile';   
   
   protected $fillable = [
        'mimi_type',
        'path',
        'width',
        'height',
        'size'
    ];
   
   public function filegroup()
   {
       return $this->belongsTo('App\Entity\UserFileGroupEntity', 'userfilegroup_id');
   }
}

?>
