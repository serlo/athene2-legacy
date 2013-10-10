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

/**
 * An entity link.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_revision_field")
 */
class RevisionField {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /** @ORM\Column(type="integer", name="entity_revision_id") */
    protected $entityRevisionId;
    
    /** @ORM\Column(type="string") */
    protected $field;
    
    /** @ORM\Column(type="string") */
    protected $value;

    /**
	 * @return field_type $entityRevisionId
	 */
	public function getEntityRevisionId() {
		return $this->entityRevisionId;
	}
	
	public function getName(){
		return $this->getField();
	}

	/**
	 * @return field_type $field
	 */
	public function getField() {
		return $this->field;
	}

	/**
	 * @return field_type $value
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @return field_type $revision
	 */
	public function getRevision() {
		return $this->revision;
	}

	/**
	 * @param field_type $entityRevisionId
	 * @return $this
	 */
	public function setEntityRevisionId($entityRevisionId) {
		$this->entityRevisionId = $entityRevisionId;
		return $this;
	}

	/**
	 * @param field_type $field
	 * @return $this
	 */
	public function setField($field) {
		$this->field = $field;
		return $this;
	}

	/**
	 * @param field_type $value
	 * @return $this
	 */
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}

	/**
	 * @param field_type $revision
	 * @return $this
	 */
	public function setRevision($revision) {
		$this->revision = $revision;
		return $this;
	}

	public function __construct($revision, $field)
    {
        $this->entityRevisionId = $revision;
        $this->field = $field;
    }
    
    public function get($property){
        return $this->$property;
    }
    
    public function set($property, $value){
        return $this->$property = $value;
    }
    
	/**
	 * @ORM\ManyToOne(targetEntity="Revision", inversedBy="fields", cascade={"persist"})
     * @ORM\JoinColumn(name="entity_revision_id", referencedColumnName="id")
	 **/
	protected $revision;
}
