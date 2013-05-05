<?php

namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Versioning\Entity\RevisionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Versioning\Entity\RepositoryInterface;

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

	public function addValue($field, $value){
	    $entity = new RevisionValue($field, $this->getId());
	    $entity->set('field', $field);
	    $entity->set('revision', $this);
	    $entity->set('value', $value);
	    $this->revisionValues->add($entity);
	    return $entity;
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
	
	public function setRepository(RepositoryInterface $repository){
	    $this->repository = $repository;
	    return $this;
	}
	
	/* (non-PHPdoc)
	 * @see \Versioning\Entity\RevisionInterface::getRepository()
	 */
	public function getRepository() {
		return $this->repository;
	}
	
	public function __construct(){
        $this->revisionValues = new \Doctrine\Common\Collections\ArrayCollection();
	}
}
