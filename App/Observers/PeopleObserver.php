<?php

namespace App\Observers;

use App\Application,
    Illuminate\Database\Eloquent\Relations\Pivot,
    Illuminate\Database\Eloquent\Model;

class PeopleObserver {
    
    /**
     * 
     * @param Pivot $pivot
     */
    public function pivotSaving($pivot)
    {
        switch($pivot->getTable()) {
            case 'people_area':
                $db = Application::getCapsule()->connection();
        
                if(!$db) {
                    return;
                }
                
                if($pivot->area_id > 0 && $pivot->people_id > 0) {
                    $db->select($db->raw("select `link_people_to_upper_area`($pivot->people_id, $pivot->area_id);"));
                }
                
                break;
        }
    }
}
