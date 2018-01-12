<?php

namespace App\Entity;

class FileEntity extends AbstractEntity {
   
    protected $table = 'file';

    protected $fillable = [
        'mimi_type',
        'path',
        'width',
        'height',
        'size'
    ];

    public function group()
    {
        return $this->belongsTo('App\Entity\FileGroupEntity', 'filegroup_id');
    }
}

?>
