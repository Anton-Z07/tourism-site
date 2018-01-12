<?php

namespace App\Entity;

class UserServicesEntity extends AbstractEntity {

    protected $table = 'userservices';

    public function user()
    {
        return $this->belongsTo('App\Entity\UserEntity', 'user_id');
    }
}

?>
