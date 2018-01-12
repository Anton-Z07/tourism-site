<?php

namespace App\Entity;

class PlusAndMinusEntity extends AbstractEntity {

    protected $table = 'plusandminus';
    
    public function text()
    {
        return $this->belongsTo('App\Entity\UserStringEntity', 'text_id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Entity\UserEntity', 'user_id' ,'id');
    }
    
    public function area()
    {
        return $this->belongsToMany('App\Entity\AreaEntity', 'plusandminus_area', 'plusandminus_id', 'area_id')->withTimestamps();
    }
}

?>
