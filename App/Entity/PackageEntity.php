<?php

namespace App\Entity;

class PackageEntity extends AbstractEntity {

    protected $table = 'package';
    
    protected $fillable = [
        'name',
        'hash',
        'path',
        'size',
        'created_at',
        'updated_at'
    ];
    
    public function area()
    {
        return $this->belongsTo('App\Entity\AreaEntity', 'area_id');
    }
}

?>
