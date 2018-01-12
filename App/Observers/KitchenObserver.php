<?php

namespace App\Observers;

use App\Application,
    Illuminate\Database\Eloquent\Relations\Pivot,
    Illuminate\Database\Eloquent\Model;

class KitchenObserver {
    
    /**
     * 
     * @param Pivot $pivot
     */
    public function pivotSaving($pivot)
    {
        switch($pivot->getTable()) {
            case 'kitchen_area':
                $db = Application::getCapsule()->connection();
        
                if(!$db) {
                    return;
                }
                
                if($pivot->area_id > 0 && $pivot->kitchen_id > 0) {
                    $db->select($db->raw("select `link_kitchen_to_upper_area`($pivot->kitchen_id, $pivot->area_id);"));
                }
                break;
        }
    }
}
