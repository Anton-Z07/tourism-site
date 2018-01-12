<?php

namespace App\Model\Manager\Routes;

interface ChildishRouteInterface {
    
    function getField();
    function getParent();
    function getOptions();
    
    function onParentInit();
    function onParentModifier();
    function onParentEnd();
}

?>
