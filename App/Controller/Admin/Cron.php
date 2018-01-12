<?php

namespace App\Controller\Admin;

use App\Application;

class Cron {

    public function test()
    {
        $db = Application::getCapsule()->connection();
        return $db->select($db->raw('select `test`();'));
    }
}
