<?php

namespace App\View\Strategy;

use Luracast\Restler\Format\JsonFormat;

class HalJsonStrategy extends JsonFormat {
    
    const MIME = 'application/hal+json';
}

?>
