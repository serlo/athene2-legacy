<?php
namespace Entity\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * An entity link.
 *
 * @ORM\Entity
 * @ORM\Table(name="entity_revision_value")
 */
class RevisionValue {
	
    /** @ORM\Id @ORM\Column(type="integer", name="entity_revision_id") */
    protected $entityRevisionId;
    
    /** @ORM\Id @ORM\Column(type="string") */
    protected $field;
    
    /** @ORM\Column(type="string") */
    protected $value;

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
	 * @ORM\ManyToOne(targetEntity="Revision", inversedBy="revisionValues")
     * @ORM\JoinColumn(name="entity_revision_id", referencedColumnName="id")
	 **/
	protected $revision;
}
