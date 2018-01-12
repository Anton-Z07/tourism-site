<?php

namespace App\Model;

use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection {
    
    public function range($start = 0, $end = null)
    {
        return $this->slice($start, $end);
    }
}
