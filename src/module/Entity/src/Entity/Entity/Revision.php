<?php

namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Versioning\Entity\RevisionInterface;

/**
 * An entity link.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_revision")
 */
class Revision extends AbstractEntity implements RevisionInterface {
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
		$criteria = Criteria::create()
		    ->where(Criteria::expr()->eq("field", $field))
		    ->setFirstResult(0)
		    ->setMaxResults(1);
		$data = $this->revisionValues->matching($criteria);
		if(count($data) == 0)
			throw new \Exception('Field `'.$field.'` not found');
		return $data[0]->set('value', $key);
	}

	public function newValue($field){
	    $value = new RevisionValue();
	    $value->set('field', $field);
	    $value->set('revision', $this);
	    $this->revisionValues->add($value);
	    return $value;
	}
	
	/* (non-PHPdoc)
	 * @see \Versioning\Entity\RevisionInterface::delete()
	 */
	public function delete() {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see \Versioning\Entity\RevisionInterface::trash()
	 */
	public function trash() {
		// TODO Auto-generated method stub
		
	}
	
	/* (non-PHPdoc)
	 * @see \Versioning\Entity\RevisionInterface::getRepository()
	 */
	public function getRepository() {
		return $this->repository;
	}
}
