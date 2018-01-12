<?php

namespace App\Model\Validator;

interface ValidatorInterface {
    
    function invalidate($data, $options);
}

?>
