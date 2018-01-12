<?php

namespace App\Entity;

class PeopleEntity extends AbstractEntity {

    protected $table = 'people';
    
    public function name()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'name_id');
    }
    
    public function original_name()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'original_name_id');
    }
    
    public function detail()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'detail_id');
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
        return $this->belongsToMany('App\Entity\AreaEntity', 'people_area', 'people_id', 'area_id')->withPivot('system')->withTimestamps();
    }
    
    public function comment()
    {
        return $this->belongsToMany('App\Entity\CommentEntity', 'people_comment', 'people_id', 'comment_id')->withTimestamps();
    }
    
    public function like()
    {
        return $this->belongsToMany('App\Entity\LikeEntity', 'people_like', 'people_id', 'like_id')->withTimestamps();
    }
}

?>
