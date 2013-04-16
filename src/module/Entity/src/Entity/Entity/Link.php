<?php
namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * An entity link.
 *
 * @ORM\Entity
 * @ORM\Table(name="link")
 */
class Link extends AbstractEntity {    
    public function __construct() {
    }
}
