<?php

namespace Controllers;

use Exception;
use Services\Taskservice;

class Taskcontroller extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new Taskservice();
    }

   
}
