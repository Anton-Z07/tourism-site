<?php

namespace App\Observers;

use App\Application,
    Illuminate\Database\Eloquent\Relations\Pivot,
    Illuminate\Database\Eloquent\Model;

class FeatureObserver {
    
    /**
     * 
     * @param Pivot $pivot
     */
    public function pivotSaving($pivot)
    {
        switch($pivot->getTable()) {
            case 'feature_area':
                $db = Application::getCapsule()->connection();
        
                if(!$db) {
                    return;
                }
                
                if($pivot->area_id > 0 && $pivot->feature_id > 0) {
                    $db->select($db->raw("select `link_feature_to_upper_area`($pivot->feature_id, $pivot->area_id);"));
                }
                break;
        }
    }
}
