<?php
namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * An entity link.
 *
 * @ORM\Entity
 * @ORM\Table(name="repository")
 */
class Repository extends AbstractEntity {
	/**
	 * @ORM\ManyToOne(targetEntity="Entity", inversedBy="repositories")
	 **/
	protected $entity;
}
