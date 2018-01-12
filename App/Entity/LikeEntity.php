<?php

namespace App\Entity;

class LikeEntity extends AbstractEntity {

    protected $table = 'like';
    
    public function user()
    {
        return $this->belongsTo('App\Entity\UserEntity', 'user_id');
    }
   
    public function area()
    {
        return $this->belongsToMany('App\Entity\AreaEntity', 'area_like', 'like_id', 'area_id')->withTimestamps();
    }
    
    public function comment()
    {
        return $this->belongsToMany('App\Entity\CommentEntity', 'comment_like', 'like_id', 'comment_id')->withTimestamps();
    }
    
    public function post()
    {
        return $this->belongsToMany('App\Entity\PostEntity', 'post_like', 'like_id', 'post_id')->withTimestamps();
    }
    
    public function people()
    {
        return $this->belongsToMany('App\Entity\PeopleEntity', 'people_like', 'like_id', 'people_id')->withTimestamps();
    }
    
    public function news()
    {
        return $this->belongsToMany('App\Entity\NewsEntity', 'news_like', 'like_id', 'news_id')->withTimestamps();
    }
    
    public function goods()
    {
        return $this->belongsToMany('App\Entity\GoodsEntity', 'goods_like', 'like_id', 'goods_id')->withTimestamps();
    }
    
    public function landmark()
    {
        return $this->belongsToMany('App\Entity\LandmarkEntity', 'landmark_like', 'like_id', 'landmark_id')->withTimestamps();
    }
    
    public function event()
    {
        return $this->belongsToMany('App\Entity\EventEntity', 'event_like', 'like_id', 'event_id')->withTimestamps();
    }
    
    public function iblock()
    {
        return $this->belongsToMany('App\Entity\IblockEntity', 'iblock_like', 'like_id', 'iblock_id')->withTimestamps();
    }
    
    public function contest()
    {
        return $this->belongsToMany('App\Entity\ContestEntity', 'contest_like', 'like_id', 'contest_id')->withTimestamps();
    }
    
    public function route()
    {
        return $this->belongsToMany('App\Entity\RouteEntity', 'route_like', 'like_id', 'route_id')->withTimestamps();
    }
    
    public function kitchen()
    {
        return $this->belongsToMany('App\Entity\KitchenEntity', 'kitchen_like', 'like_id', 'kitchen_id')->withTimestamps();
    }
    
    public function feature()
    {
        return $this->belongsToMany('App\Entity\FeatureEntity', 'feature_like', 'like_id', 'feature_id')->withTimestamps();
    }
}

?>
