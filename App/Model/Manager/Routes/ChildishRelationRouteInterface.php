<?php

namespace App\Model\Manager\Routes;

interface ChildishRelationRouteInterface extends ChildishRouteInterface {
    
    function getForeignKey();
    function getSelfKeyKey();
}
