<?php

namespace App\Observers;

use App\Application,
    Illuminate\Database\Eloquent\Relations\Pivot,
    Illuminate\Database\Eloquent\Model;

class EventObserver {
    
    /**
     * 
     * @param Pivot $pivot
     */
    public function pivotSaving($pivot)
    {
        switch($pivot->getTable()) {
            case 'event_area':
                $db = Application::getCapsule()->connection();
        
                if(!$db) {
                    return;
                }
                
                if($pivot->area_id > 0 && $pivot->event_id > 0) {
                    $db->select($db->raw("select `link_event_to_upper_area`($pivot->event_id, $pivot->area_id);"));
                }
                break;
        }
    }
}
