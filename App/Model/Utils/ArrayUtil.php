<?php

namespace App\Model\Utils;

class ArrayUtil {
    
    public static function isSequentialArray($arr, $strikt = true)
    {
        //var_dump($arr);
        foreach($arr as $key=>$val) {
            if(($strikt && !is_int($key)) || (!$strikt && !is_int(intval($key)))) {
                return false;
            }
        }
        
        return true;
    }
    
    public static function isAssoc($arr)
    {
        return count($arr) > 0 ? array_keys($arr) !== range(0, count($arr) - 1) : false;
    }
}

?>
