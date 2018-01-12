<?php

namespace App\Entity;

class UserEntity extends AbstractEntity {

    protected $table = 'user';

    public function profile()
    {
        return $this->hasOne('App\Entity\UserProfileEntity', 'user_id');
    }
    
    public function services()
    {
        return $this->hasMany('App\Entity\UserServicesEntity', 'user_id');
    }
    
    public function like()
    {
        return $this->hasMany('App\Entity\LikeEntity', 'user_id');
    }
}

?>
