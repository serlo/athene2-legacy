<?php
namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * An entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity")
 */
class Entity extends AbstractEntity {
	public function __construct() {
	}
}