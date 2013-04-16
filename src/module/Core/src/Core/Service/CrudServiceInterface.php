<?php 

namespace Core\Service;

interface CrudServiceInterface {
    public function create(array $data);
    public function receive($id = NULL);
    public function update(array $data);
    public function delete($id = NULL);
}