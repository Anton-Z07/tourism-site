<?php

namespace App\Entity;

class SiteStringEntity extends AbstractEntity {
   
   protected $table = 'sitestring';
   
    protected $fillable = [
        'resource_type',
        'ru',
        'en'
    ];
}

?>
