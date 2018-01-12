<?php

namespace App\Entity;

class LandmarkPropertyEntity extends AbstractEntity {

    protected $table = 'landmarkproperty';
    
    public function name()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'name_id');
    }
    
    public function icon()
    {
        return $this->belongsTo('App\Entity\FileGroupEntity', 'icon_id');
    }
    
    public function landmark()
    {
        return $this->belongsToMany('App\Entity\LandmarkEntity', 'landmark_landmarkproperty', 'landmarkproperty_id', 'landmark_id')->withTimestamps();
    }
    
    public function lower()
    {
        return $this->belongsToMany('App\Entity\LandmarkPropertyEntity', 'landmarkpropertylinks', 'parent_id', 'child_id')->withTimestamps();
    } 

    public function upper()
    {
        return $this->belongsToMany('App\Entity\LandmarkPropertyEntity', 'landmarkpropertylinks', 'child_id', 'parent_id')->withTimestamps();
    }
}

?>
