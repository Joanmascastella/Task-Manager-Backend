<?php
namespace Services;

use Repositories\ActivityRepository;

class Activityservice {

    private $repository;

    function __construct()
    {
        $this->repository = new ActivityRepository();
    }

}

?>