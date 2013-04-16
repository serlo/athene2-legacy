<?php
namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="component")
 */
class Component extends AbstractEntity {    
    public function __construct() {
    }
}