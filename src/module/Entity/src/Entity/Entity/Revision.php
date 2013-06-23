<?php
namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Versioning\Entity\RevisionInterface;
use Versioning\Entity\RepositoryInterface;

/**
 * An entity link.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_revision")
 */
class Revision extends AbstractEntity implements RevisionInterface
{

    /**
     * @ORM\ManyToOne(targetEntity="Entity", inversedBy="revisions")
     */
    protected $repository;

    /**
     * @ORM\OneToMany(targetEntity="RevisionValue", mappedBy="revision")
     */
    protected $revisionValues;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     */
    protected $author;

    /**
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $date;

    /**
     * @ORM\Column(type="boolean", options={"default"})
     */
    protected $trashed;

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
    public function setDate($date)
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
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    public function get($field)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("field", $field))
            ->setFirstResult(0)
            ->setMaxResults(1);
        $data = $this->revisionValues->matching($criteria);
        if (count($data) == 0)
            throw new \Exception('Field `' . $field . '` not found');
        return $data[0]->get('value');
    }

    public function set($field, $key)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("field", $field))
            ->setFirstResult(0)
            ->setMaxResults(1);
        $data = $this->revisionValues->matching($criteria);
        if (count($data) == 0)
            throw new \Exception('Field `' . $field . '` not found');
        
        return $data[0]->set('value', $key);
    }

    public function addValue($field, $value)
    {
        $entity = new RevisionValue($field, $this->getId());
        $entity->set('field', $field);
        $entity->set('revision', $this);
        $entity->set('value', $value);
        $this->revisionValues->add($entity);
        return $entity;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RevisionInterface::delete()
     */
    public function delete()
    {
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RevisionInterface::trash()
     */
    public function trash()
    {
        $this->trashed = TRUE;
        return $this;
    }

    public function untrash()
    {
        $this->trashed = FALSE;
        return $this;
    }

    public function isTrashed()
    {
        return $this->trashed;
    }

    public function toggleTrashed()
    {
        $this->trashed = ! $this->isTrashed();
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
        $this->revisionValues = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->unTrash();
        $this->trashed = false;
    }
}
