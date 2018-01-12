<?php
namespace v1;

use App\Application,
    Luracast\Restler\RestException,
    App\Model\Utils\MarketProcessor;

class Market
{
    /**
     * @param array $data {@from body}
     * smart-auto-routing false
     * @return array
     */
    public function post($data = [])
    {
        if(!is_array($data) || !isset($data['market']) || !($options = Application::getMarketFromCode(trim($data['market'])))) {
            throw new RestException(400, "Market {$data['market']} not found!");
        }
        
        $products = MarketProcessor::getProductsFromTokens($data['market'], $options, $data['products']);
        
        if(count($products) < 1) {
            return [];
        }
        $query = (new Package())->getQuery();
        
        $query->whereHas('area', function($query) use($products) {
            $query->getQuery()->whereIn('hash', array_keys($products), 'and');
        });
        /*foreach(array_keys($products) as $hash) {
            $query->orWhere('hash', '=', $hash);
        }*/
        
        $collection = $query->with('area')->get();
        /*->keyBy('area_id');
        
        foreach($products as $id=>$item) {
            if(($found = $collection->get($id, false)) !== false) {
                $products[$id] = $found;
            } else {
                unset($products[$id]);
            }
        }*/
        
        //print_r($products);
        return $collection;
    }
}

