<?php
namespace Services;

use Repositories\Taskrepository;

class Taskservice {

    private $repository;

    function __construct()
    {
        $this->repository = new Taskrepository();
    }

}

?>