<?php

namespace v1;

use App\Controller\AbstractController,
    App\Entity\AreaEntity,
    App\Entity\LandmarkEntity,
    App\Entity\KitchenEntity,
    App\Entity\PeopleEntity,
    App\Entity\FeatureEntity,
    Luracast\Restler\RestException,
    App\Model\Collection;

class Search extends AbstractController {
    
    public function index($filter = '', $order = '', $fields = '', $page = null, $pagesize = null)
    {
        $page = intval($page) > 0 ? intval($page) : 1;
        $pageSize = intval($pagesize) > 0 ? intval($pagesize) : 20;
        
        $areaCm = static::createConditionsMap('v1\Area', '', $filter, '');
        $landmarkCm = static::createConditionsMap('v1\Landmark', '', $filter, '');
        $peopleCm = static::createConditionsMap('v1\People', '', $filter, '');
        $kitchenCm = static::createConditionsMap('v1\Kitchen', '', $filter, '');
        $featureCm = static::createConditionsMap('v1\Feature', '', $filter, '');
        
        if(!$areaCm->hasRouteConditionFilter() || !$landmarkCm->hasRouteConditionFilter() || !$peopleCm->hasRouteConditionFilter() || !$kitchenCm->hasRouteConditionFilter() || !$featureCm->hasRouteConditionFilter()) {
            throw new RestException(400, "No searching filter!");
        }
        
        $areaList = static::getCollection((new AreaEntity())->newQuery(), $areaCm, 1, $pageSize);
        $peopleList = static::getCollection((new PeopleEntity())->newQuery(), $peopleCm, 1, $pageSize);
        $landmarkList = static::getCollection((new LandmarkEntity())->newQuery(), $landmarkCm, 1, $pageSize);
        $kitchenList = static::getCollection((new KitchenEntity())->newQuery(), $kitchenCm, 1, $pageSize);
        $featureList = static::getCollection((new FeatureEntity())->newQuery(), $featureCm, 1, $pageSize);
        
        $result = [];
        
        $mapper = function($item, $key, $type) use(&$result) {
            $result[] = [
                'type' => $type,
                'item' => $item,
            ];
        };
        
        array_walk($areaList, $mapper, 1);
        array_walk($landmarkList, $mapper, 2);
        array_walk($peopleList, $mapper, 3);
        array_walk($featureList, $mapper, 4);
        array_walk($kitchenList, $mapper, 5);
        
        $collection = new Collection($result);
        
        $total = $collection->count();
        return [
            'total' => $total,
            'page' => $page,
            'pagesize' => $pageSize,
            'results' => $collection->range(($page - 1) * $pageSize, $pageSize)
        ];
    }
}
