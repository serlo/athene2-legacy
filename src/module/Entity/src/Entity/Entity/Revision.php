<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Versioning\Entity\RevisionInterface;
use Versioning\Entity\RepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use User\Entity\UserInterface;
use Uuid\Entity\UuidEntity;

/**
 * An entity link.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_revision")
 */
class Revision extends UuidEntity implements RevisionInterface
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="user")
     * @ORM\JoinColumn(name="id")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Entity", inversedBy="revisions")
     */
    protected $repository;

    /**
     * @ORM\OneToMany(targetEntity="RevisionField", mappedBy="revision", cascade={"persist"})
     */
    protected $fields;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     */
    protected $author;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $date;
    
    public function getFields(){
    	$collection = new ArrayCollection();
    	foreach($this->fields as $field){
    		$collection->set($this->getRepository()->getFieldOrder($field->getName()), $field);
    	}
    	return $collection;
    }

    /**
     *
     * @return field_type $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     *
     * @param field_type $date            
     * @return $this
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     *
     * @return field_type $author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     *
     * @param field_type $author            
     * @return $this
     */
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
        return $this;
    }

    public function get($field)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("field", $field))
            ->setFirstResult(0)
            ->setMaxResults(1);
        $data = $this->fields->matching($criteria);
        if (count($data) == 0)
            throw new \Exception('Field `' . $field . '` not found');
        return $data[0]->get('value');
    }

    public function set($field, $key)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("field", $field))
            ->setFirstResult(0)
            ->setMaxResults(1);
        $data = $this->fields->matching($criteria);
        if (count($data) == 0)
            throw new \Exception('Field `' . $field . '` not found');
        
        return $data[0]->set('value', $key);
    }

    public function addField($name, $value)
    {
        $entity = new RevisionField($name, $this->getId());
        $entity->set('field', $name);
        $entity->set('revision', $this);
        $entity->set('value', $value);
        $this->fields->add($entity);
        return $entity;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RevisionInterface::delete()
     */
    public function delete()
    {
        return $this;
    }

    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RevisionInterface::getRepository()
     */
    public function getRepository()
    {
        return $this->repository;
    }

    public function __construct()
    {
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
