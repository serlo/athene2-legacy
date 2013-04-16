<?php
namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * An entity type.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_type")
 */
class EntityType extends AbstractEntity {    
    public function __construct() {
    }
}