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

    function create(){

    }

    function getAll(){

    }
    
    function getOne(){

    }
    
    function update(){

    }
    
    function delete(){

    }

    function complete(){

    }

    function shareSingle(){

    }
   
}
