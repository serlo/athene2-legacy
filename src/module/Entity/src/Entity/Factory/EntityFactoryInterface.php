<?php
namespace Entity\Factory;

use Core\Entity\ModelInterface;

interface EntityFactoryInterface extends ModelInterface
{
    public function getClassName($name);
}