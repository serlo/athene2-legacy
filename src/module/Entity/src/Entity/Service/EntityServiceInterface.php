<?php
namespace Entity\Service;

use Core\Entity\ModelInterface;

interface EntityServiceInterface extends ModelInterface
{
    public function getClassName($name);
}