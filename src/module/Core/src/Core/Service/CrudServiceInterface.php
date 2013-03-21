<?php 

namespace Core\Service;

interface CrudServiceInterface {
    public function create();
    public function receive();
    public function update();
    public function delete();
}