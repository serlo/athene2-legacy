<?php
namespace Entity\Service;

use Core\Entity\ModelInterface;

interface EntityServiceInterface extends ModelInterface
{
    public function kill();
    public function receive($id);
    
    public function persist();
    public function create();
    
    /*public function getRepositories();
    public function getRepository();
    
    public function getComments();
    public function getComment($id);
    public function addComment($comment);
    
    public function hasTag($tag);
    public function getTags();
    public function getTag($id);
    public function addTag($tag);*/
}