<?php

namespace App\Entity;

class EventEntity extends AbstractEntity {

    protected $table = 'event';
    
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
    
    public function date()
    {
        return $this->hasMany('App\Entity\EventDateEntity', 'event_id');
    }
    
    public function area()
    {
        return $this->belongsToMany('App\Entity\AreaEntity', 'event_area', 'event_id', 'area_id')->withPivot('system')->withTimestamps();
    }
    
    public function iblock()
    {
        return $this->belongsToMany('App\Entity\IblockEntity', 'iblock_event', 'event_id', 'iblock_id')->withTimestamps();
    }
    
    public function comment()
    {
        return $this->belongsToMany('App\Entity\CommentEntity', 'event_comment', 'event_id', 'comment_id')->withTimestamps();
    }
    
    public function like()
    {
        return $this->belongsToMany('App\Entity\LikeEntity', 'event_like', 'event_id', 'like_id')->withTimestamps();
    }
}

?>
