<?php

namespace App\Observers;

use App\Application,
    Illuminate\Database\Eloquent\Relations\Pivot,
    Illuminate\Database\Eloquent\Model;
/**
 * Система обсерверов на различные события, связанные с манипуляциями с моделями.
 * Работает всё просто, есть ключевые слова, на действия совершаймые с моделью, если в классе есть этот метод, он будет вызван по шорт имяни. Например событие saving вызовит
 * метод Saving в классе обсервера, если он есть. Важно, чтобы имя класс обсервера начиналось подобна модели. Например, для мдели AreaEntity, нужен обсервер в виде AreaObserver.
 * Всё цепляется овтоматически. 
 * Для перехвата событей, связанных с пивот полями, нужно перед методом перехвата, добавить слово pivot и отследить по переданному параметру, с какой таблицей связан данный pivot.
 * Например, что бы перехватит события на изменение связавание Area и People, в классе AreaObserver(или\и в классе PeopleObserver) нужно создать соотвественный метод(например pivotSaving).
 * Ниже, список всех возможных перехватчиков:
 * 'booting', 'booted', 'creating', 'created', 'updating', 'updated', 'deleting', 'deleted', 'saving', 'saved', 'restoring', 'restored'
 * По моим наблюдениям, для полей пивот не работают загрузочные перехватчики('booting', 'booted') и пост перехватчики(на данный момент, я проверил только 'saved' - он не работает!, вместо него, нужно пользоватся 'saving')
 */
class AreaObserver {
    
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
            case 'kitchen_area':
                $db = Application::getCapsule()->connection();
        
                if(!$db) {
                    return;
                }
                
                if($pivot->area_id > 0 && $pivot->kitchen_id > 0) {
                    $db->select($db->raw("select `link_kitchen_to_upper_area`($pivot->kitchen_id, $pivot->area_id);"));
                }
                break;
            case 'feature_area':
                $db = Application::getCapsule()->connection();
        
                if(!$db) {
                    return;
                }
                
                if($pivot->area_id > 0 && $pivot->feature_id > 0) {
                    $db->select($db->raw("select `link_feature_to_upper_area`($pivot->feature_id, $pivot->area_id);"));
                }
                break;                
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
