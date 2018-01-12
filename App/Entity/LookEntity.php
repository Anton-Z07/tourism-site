<?php

namespace App\Entity;

class LookEntity extends AbstractEntity {

    protected $table = 'look';
   
    public function user()
    {
        return $this->belongsTo('App\Entity\UserEntity', 'user_id');
    }
   
    public function area()
    {
        return $this->belongsToMany('App\Entity\AreaEntity', 'area_look', 'look_id', 'area_id')->withTimestamps();
    }
    
    public function landmark()
    {
        return $this->belongsToMany('App\Entity\LandmarkEntity', 'landmark_look', 'look_id', 'landmark_id')->withTimestamps();
    }    
    
    public function route()
    {
        return $this->belongsToMany('App\Entity\RouteEntity', 'route_look', 'look_id', 'route_id')->withTimestamps();
    }
}

?>
