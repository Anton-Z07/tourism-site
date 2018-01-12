<?php

namespace App\Entity;

class AreaEntity extends AbstractEntity {

    protected $table = 'area';

    public function name()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'name_id');
    }

    public function detail()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'detail_id');
    }

    public function transport_text()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'transport_text_id');
    }

    public function history_text()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'history_text_id');
    }

    public function landmark_text()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'landmark_text_id');
    }

    public function kitchen_text()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'kitchen_text_id');
    }

    public function people_text()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'people_text_id');
    }

    public function event_text()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'event_text_id');
    }
    
    public function map()
    {
        return $this->belongsTo('App\Entity\FileGroupEntity', 'map_id');
    }
    
    public function flag()
    {
        return $this->belongsTo('App\Entity\FileGroupEntity', 'flag_id');
    }
    
    public function image()
    {
        return $this->belongsTo('App\Entity\FileGroupEntity', 'image_id');
    }
    
    public function mobile_image()
    {
        return $this->belongsTo('App\Entity\FileGroupEntity', 'mobile_image_id');
    }
    
    public function mobile_map_file()
    {
        return $this->belongsTo('App\Entity\FileGroupEntity', 'mobile_map_file_id');
    }
    
    public function empty_image()
    {
        return $this->belongsTo('App\Entity\FileGroupEntity', 'empty_image_id');
    }

    public function people()
    {
        return $this->belongsToMany('App\Entity\PeopleEntity', 'people_area', 'area_id', 'people_id')->withPivot('system')->withTimestamps();
    }
    
    public function landmark()
    {
        return $this->belongsToMany('App\Entity\LandmarkEntity', 'landmark_area', 'area_id', 'landmark_id')->withPivot('vicinity', 'distance', 'system')->withTimestamps();
    }
    
    public function event()
    {
        return $this->belongsToMany('App\Entity\EventEntity', 'event_area', 'area_id', 'event_id')->withPivot('system')->withTimestamps();
    }
    
    public function tour()
    {
        return $this->hasMany('App\Entity\TourEntity', 'area_id', 'id');
    }
    
    public function routepoint()
    {
        return $this->hasMany('App\Entity\RoutePointEntity', 'area_id', 'id');
    }
    
    public function feature()
    {
        return $this->belongsToMany('App\Entity\FeatureEntity', 'feature_area', 'area_id', 'feature_id')->withPivot('system')->withTimestamps();
    }
    
    public function plusandminus()
    {
        return $this->belongsToMany('App\Entity\PlusAndMinusEntity', 'plusandminus_area', 'area_id', 'plusandminus_id')->withTimestamps();
    }
    
    public function kitchen()
    {
        return $this->belongsToMany('App\Entity\KitchenEntity', 'kitchen_area', 'area_id', 'kitchen_id')->withPivot('system')->withTimestamps();
    }

    public function lower()
    {
        return $this->belongsToMany('App\Entity\AreaEntity', 'arealinks', 'parent_id', 'child_id')->withPivot('capital')->withTimestamps();
    } 

    public function upper()
    {
        return $this->belongsToMany('App\Entity\AreaEntity', 'arealinks', 'child_id', 'parent_id')->withPivot('capital')->withTimestamps();
    }
    
    public function gallery()
    {
        return $this->belongsToMany('App\Entity\UserFileGroupEntity', 'area_gallery', 'area_id', 'user_file_id')->withTimestamps();
    }
    
    public function post()
    {
        return $this->belongsToMany('App\Entity\PostEntity', 'post_area', 'area_id', 'post_id')->withTimestamps();
    }
    
    public function like()
    {
        return $this->belongsToMany('App\Entity\LikeEntity', 'area_like', 'area_id', 'like_id')->withTimestamps();
    }
    
    public function look()
    {
        return $this->belongsToMany('App\Entity\LookEntity', 'area_look', 'area_id', 'look_id')->withTimestamps();
    }
    
    public function package()
    {
        return $this->hasOne('App\Entity\PackageEntity', 'area_id', 'id');
    }
}

?>
