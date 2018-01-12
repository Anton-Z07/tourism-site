<?php

namespace App\Entity;

class UserProfileEntity extends AbstractEntity {
    
   public $timestamps = false;
   protected $table = 'user_profile';   
   
   public function user()
   {
       return $this->belongsTo('App\Entity\UserEntity', 'user_id');
   }
   
   public function avatar()
   {
       return $this->belongsTo('App\Entity\UserFileGroupEntity', 'avatar_id');
   }
}

?>
