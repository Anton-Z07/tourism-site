<?php

namespace App\Entity;

class UserStringEntity extends AbstractEntity {
   
   protected $table = 'userstring';
   
   protected $fillable = [
        'resource_type',
        'text',
        'status'
    ];
   
   public function comment()
   {
       return $this->belongsTo('App\Entity\CommentEntity', 'id' ,'text_id');
   }
}

?>
