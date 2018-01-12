<?php

namespace App\Entity;

class LandmarkEntity extends AbstractEntity {

    protected $table = 'landmark';
    
    public function name()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'name_id');
    }
    
    public function original_name()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'original_name_id');
    }
    
    public function text()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'text_id');
    }
    
    public function full_text()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'full_text_id');
    }
    
    public function image()
    {
        return $this->belongsTo('App\Entity\FileGroupEntity', 'image_id');
    }
    
    public function area()
    {
        return $this->belongsToMany('App\Entity\AreaEntity', 'landmark_area', 'landmark_id', 'area_id')->withPivot('vicinity','distance', 'system')->withTimestamps();
    }
    
    public function property()
    {
        return $this->belongsToMany('App\Entity\LandmarkPropertyEntity', 'landmark_landmarkproperty', 'landmark_id', 'landmarkproperty_id')->withTimestamps();
    }
    
    public function childiblock()
    {
        return $this->belongsToMany('App\Entity\IblockEntity', 'landmark_iblock', 'landmark_id', 'iblock_id')->withTimestamps();
    }
    
    public function parentiblock()
    {
        return $this->belongsToMany('App\Entity\IblockEntity', 'iblock_landmark', 'landmark_id', 'iblock_id')->withTimestamps();
    }
    
    public function routepoint()
    {
        return $this->belongsToMany('App\Entity\RoutePointEntity', 'routepoint_landmark', 'landmark_id', 'routepoint_id')->withPivot('recommended')->withTimestamps();
    }
    
    public function route()
    {
        return $this->belongsToMany('App\Entity\RouteEntity', 'route_landmark', 'landmark_id', 'route_id')->withTimestamps();
    }
    
    public function gallery()
    {
        return $this->belongsToMany('App\Entity\UserFileGroupEntity', 'landmark_gallery', 'landmark_id', 'user_file_id')->withTimestamps();
    }
    
    public function comment()
    {
        return $this->belongsToMany('App\Entity\CommentEntity', 'landmark_comment', 'landmark_id', 'comment_id')->withTimestamps();
    }
    
    public function like()
    {
        return $this->belongsToMany('App\Entity\LikeEntity', 'landmark_like', 'landmark_id', 'like_id')->withTimestamps();
    }
    
    public function look()
    {
        return $this->belongsToMany('App\Entity\LookEntity', 'landmark_look', 'landmark_id', 'look_id')->withTimestamps();
    }
    
    public function closest_landmarks()
    {
        //return self::where('id < 100')->take(10)->get();
        return $this->belongsToMany('App\Entity\LookEntity', 'landmark_look', 'landmark_id', 'look_id')->withTimestamps();
    }
}

?>
