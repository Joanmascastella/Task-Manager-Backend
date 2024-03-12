<?php

namespace Services;

use Models\ListModel;
use Repositories\ListRepository;

class ListService {

    private $repository;

    function __construct()
    {
        $this->repository = new ListRepository();
    }


    public function create(ListModel $list) {
        return $this->repository->create($list);
    }


    public function update($list_id, ListModel $list) {
        return $this->repository->update($list_id, $list);
    }


    public function delete($list_id) {
        return $this->repository->delete($list_id);
    }


    public function addTask($list_id, $task_id) {
        return $this->repository->addTask($list_id, $task_id);
    }


    public function share($list_id) {
        return $this->repository->share($list_id);
    }

}

?>
