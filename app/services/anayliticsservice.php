<?php

namespace Services;

use Repositories\AnalyticsRepository;

class AnalyticsService {

    private $repository;

    function __construct() {
        $this->repository = new AnalyticsRepository();
    }

   

}
?>