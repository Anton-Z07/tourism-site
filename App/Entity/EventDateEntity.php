<?php

namespace App\Entity;

class EventDateEntity extends AbstractEntity {

    protected $table = 'eventdate';

    public function event()
    {
        return $this->belongsTo('App\Entity\Event', 'event_id');
    }
}

?>
