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
use User\Entity\UserInterface;
use Uuid\Entity\UuidEntity;
use Common\Normalize\Normalizable;
use Common\Normalize\Normalized;

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
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid", inversedBy="entityRevision")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
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

    /**
     *
     * @return field_type $date
     */
    public function getTimestamp()
    {
        return $this->date;
    }

    /**
     *
     * @param field_type $date            
     * @return self
     */
    public function setTimestamp(\DateTime $date)
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
     * @return self
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
            return null;
        
        return $data[0]->get('value');
    }

    public function set($name, $value)
    {
        $entity = new RevisionField($name, $this->getId());
        $entity->set('field', $name);
        $entity->set('revision', $this);
        $entity->set('value', $value);
        $this->fields->add($entity);
        return $entity;
    }

    public function getFields()
    {
        return $this->fields;
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
