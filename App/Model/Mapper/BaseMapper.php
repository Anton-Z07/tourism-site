<?php

namespace App\Model\Mapper;

class BaseMapper {
    
    const RELATION_NONE = 0;
    const RELATION_BELONGSTO = 1;
    const RELATION_HASONE = 2;
    const RELATION_HASMANY = 3;
    const RELATION_BELONGSTOMANY = 4;
    
    const CONTROL_HIDDEN = 0;
    const CONTROL_ACL = 1;
    const CONTROL_VISIBLE = 2;
    
    const REQUST_ACCESS_READ = 0;
    const REQUST_ACCESS_WRITE = 1;
}

?>
