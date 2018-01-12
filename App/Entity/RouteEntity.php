<?php

namespace App\Entity;

class RouteEntity extends AbstractEntity {

    protected $table = 'route';
    
    public function name()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'name_id');
    }
    
    public function text()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'text_id');
    }
    
    public function full_text()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'full_text_id');
    }
    
    public function helpful_text()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'helpful_text_id');
    }
    
    public function landmark()
    {
        return $this->belongsToMany('App\Entity\LandmarkEntity', 'route_area', 'route_id', 'landmark_id')->withTimestamps();
    }
    
    public function points()
    {
        return $this->hasMany('App\Entity\RoutePointEntity', 'route_id');
    }
    
    public function iblock()
    {
        return $this->belongsToMany('App\Entity\IblockEntity', 'iblock_route', 'route_id', 'iblock_id')->withTimestamps();
    }
    
    public function comment()
    {
        return $this->belongsToMany('App\Entity\CommentEntity', 'route_comment', 'route_id', 'comment_id')->withTimestamps();
    }
    
    public function like()
    {
        return $this->belongsToMany('App\Entity\LikeEntity', 'route_like', 'route_id', 'like_id')->withTimestamps();
    }
    
    public function look()
    {
        return $this->belongsToMany('App\Entity\LookEntity', 'route_like', 'route_id', 'like_id')->withTimestamps();
    }
}

?>
