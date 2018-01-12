<?php

namespace App\Entity;

class CommentEntity extends AbstractEntity {

    protected $table = 'comment';
   
    public function text()
    {
        return $this->belongsTo('App\Entity\UserStringEntity', 'text_id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Entity\UserEntity', 'user_id');
    }
    
    public function like()
    {
        return $this->belongsToMany('App\Entity\LikeEntity', 'comment_like', 'comment_id', 'like_id')->withTimestamps();
    }
    
    public function people()
    {
        return $this->belongsToMany('App\Entity\PeopleEntity', 'people_comment', 'comment_id', 'people_id')->withTimestamps();
    }
    
    public function post()
    {
        return $this->belongsToMany('App\Entity\PostEntity', 'post_comment', 'comment_id', 'post_id')->withTimestamps();
    }
    
    public function landmark()
    {
        return $this->belongsToMany('App\Entity\LandmarkEntity', 'landmark_comment', 'comment_id', 'landmark_id')->withTimestamps();
    }    
    
    public function news()
    {
        return $this->belongsToMany('App\Entity\NewsEntity', 'news_comment', 'comment_id', 'news_id')->withTimestamps();
    }
    
    public function kitchen()
    {
        return $this->belongsToMany('App\Entity\KitchenEntity', 'kitchen_comment', 'comment_id', 'kitchen_id')->withTimestamps();
    }
    
    public function feature()
    {
        return $this->belongsToMany('App\Entity\FeatureEntity', 'feature_comment', 'comment_id', 'feature_id')->withTimestamps();
    }
    
    public function goods()
    {
        return $this->belongsToMany('App\Entity\GoodsEntity', 'goods_comment', 'comment_id', 'goods_id')->withTimestamps();
    }
    
    public function iblock()
    {
        return $this->belongsToMany('App\Entity\IblockEntity', 'iblock_comment', 'comment_id', 'iblock_id')->withTimestamps();
    }
    
    public function userfilegroup()
    {
        return $this->belongsToMany('App\Entity\UserFileGroupEntity', 'file_comment', 'comment_id', 'user_file_id')->withTimestamps();
    }
    
    public function event()
    {
        return $this->belongsToMany('App\Entity\EventEntity', 'event_comment', 'comment_id', 'event_id')->withTimestamps();
    }
    
    public function route()
    {
        return $this->belongsToMany('App\Entity\RouteEntity', 'route_comment', 'comment_id', 'route_id')->withTimestamps();
    }
}

?>
