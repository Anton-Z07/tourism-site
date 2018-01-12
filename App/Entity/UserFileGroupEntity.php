<?php

namespace App\Entity;

class UserFileGroupEntity extends AbstractEntity {
   
   protected $table = 'userfilegroup';
   
   public function name()
   {
       return $this->belongsTo('App\Entity\UserStringEntity', 'name_id');
   }
   
   public function user()
   {
       return $this->belongsTo('App\Entity\UserEntity', 'user_id');
   }
   
   public function files()
   {
       return $this->hasMany('App\Entity\UserFileEntity', 'userfilegroup_id');
   }
}

?>
