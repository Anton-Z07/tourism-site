<?php

namespace App\Entity;

class KitchenEntity extends AbstractEntity {

    protected $table = 'kitchen';
    
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
    
    public function image()
    {
        return $this->belongsTo('App\Entity\FileGroupEntity', 'image_id');
    }

    public function web_image()
    {
        return $this->belongsTo('App\Entity\FileGroupEntity', 'web_image_id');
    }
    
    public function area()
    {
        return $this->belongsToMany('App\Entity\AreaEntity', 'kitchen_area', 'kitchen_id', 'area_id')->withPivot('system')->withTimestamps();
    }
    
    public function iblock()
    {
        return $this->belongsToMany('App\Entity\IblockEntity', 'iblock_kitchen', 'kitchen_id', 'iblock_id')->withTimestamps();
    }
    
    public function comment()
    {
        return $this->belongsToMany('App\Entity\CommentEntity', 'kitchen_comment', 'kitchen_id', 'comment_id')->withTimestamps();
    }
    
    public function like()
    {
        return $this->belongsToMany('App\Entity\LikeEntity', 'kitchen_like', 'kitchen_id', 'like_id')->withTimestamps();
    }
}

?>
