<?php
namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * An entity link type.
 *
 * @ORM\Entity
 * @ORM\Table(name="link_type")
 */
class LinkType extends AbstractEntity {    
    public function __construct() {
    }
}