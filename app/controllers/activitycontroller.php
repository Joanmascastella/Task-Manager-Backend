<?php

namespace Controllers;

use Exception;
use Services\ActivityService;

class Activitycontroller extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new ActivityService();
    }

   
}
