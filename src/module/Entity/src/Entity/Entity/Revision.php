<?php

namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;

/**
 * An entity link.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_revision")
 */
class Revision extends AbstractEntity {
	/**
	 * @ORM\ManyToOne(targetEntity="Entity", inversedBy="revisions")
	 */
	protected $repository;
	
	/**
	 * @ORM\OneToMany(targetEntity="RevisionValue", mappedBy="revision")
	 */
	protected $revisionValues;
	
	public function get($field) {
		$criteria = Criteria::create()
		    ->where(Criteria::expr()->eq("field", $field))
		    ->setFirstResult(0)
		    ->setMaxResults(1);
		$data = $this->revisionValues->matching($criteria);
		if(count($data) == 0)
			throw new \Exception('Field `'.$field.'` not found');
		return $data[0]->get('value');
	}
	
	public function set($field, $key) {
	}
}
