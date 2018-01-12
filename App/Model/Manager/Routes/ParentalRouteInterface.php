<?php

namespace App\Model\Manager\Routes;

interface ParentalRouteInterface {
    
    function dispatchChildInit();
    //function dispatchChildSave();
    function dispatchChildModifier();
    function dispatchChildEnd();
}

?>
