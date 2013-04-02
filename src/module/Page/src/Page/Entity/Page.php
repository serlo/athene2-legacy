<?php
namespace Page\Entity;

use Core\Entity\AbstractEntity;

/**
 * A user.
 *
 * @ORM\Entity
 * @ORM\Table(name="page")
 */
class Page extends AbstractEntity {

    /**
     * @OneToMany(targetEntity="PageRepository", mappedBy="page")
     **/
    protected $translations;
    
    public function __construct() {
    	$this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
}