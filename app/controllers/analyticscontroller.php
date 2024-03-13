<?php

namespace Controllers;

require __DIR__ . '/../vendor/autoload.php';
use Exception;

use Services\AnalyticsService;

class Analyticscontroller extends Controller
{
    private $service;

    function __construct()
    {
        parent::__construct();
        $this->service = new AnalyticsService();
    }




  


}
