<?php

namespace App\Entity;

class FeatureEntity extends AbstractEntity {

    protected $table = 'feature';
    
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
        return $this->belongsToMany('App\Entity\AreaEntity', 'feature_area', 'feature_id', 'area_id')->withPivot('system')->withTimestamps();
    }
    
    public function iblock()
    {
        return $this->belongsToMany('App\Entity\IblockEntity', 'iblock_feature', 'feature_id', 'iblock_id')->withTimestamps();
    }
    
    public function comment()
    {
        return $this->belongsToMany('App\Entity\CommentEntity', 'feature_comment', 'feature_id', 'comment_id')->withTimestamps();
    }
    
    public function like()
    {
        return $this->belongsToMany('App\Entity\LikeEntity', 'feature_like', 'feature_id', 'like_id')->withTimestamps();
    }
}

?>
