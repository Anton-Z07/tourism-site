<?php

namespace App\Entity;

class RoutePointEntity extends AbstractEntity {

    protected $table = 'routepoint';

    public function name()
    {
        return $this->belongsTo('App\Entity\SiteStringEntity', 'name_id');
    }

    public function haltpoint()
    {
        return $this->belongsToMany('App\Entity\RoutePointEntity', 'routepointlinks', 'halt_routepoint_id', 'haltless_routepoint_id')->withTimestamps();
    } 

    public function haltlesspoint()
    {
        return $this->belongsToMany('App\Entity\RoutePointEntity', 'routepointlinks', 'haltless_routepoint_id', 'halt_routepoint_id')->withTimestamps();
    }
    
    public function landmark()
    {
        return $this->belongsToMany('App\Entity\LandmarkEntity', 'routepoint_landmark', 'routepoint_id', 'landmark_id')->withPivot('recommended')->withTimestamps();
    }
    
    public function route()
    {
        return $this->belongsTo('App\Entity\RouteEntity', 'route_id' ,'id');
    }
    
    public function area()
    {
        return $this->belongsTo('App\Entity\AreaEntity', 'area_id' ,'id');
    }
}

?>
