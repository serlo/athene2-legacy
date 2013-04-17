<?php
namespace Page\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * A user.
 *
 * @ORM\Entity
 * @ORM\Table(name="page")
 */
class Page extends AbstractEntity {

    /**
     * @ORM\OneToMany(targetEntity="PageRepository", mappedBy="page")
     **/
    protected $translations;
    
    public function __construct() {
    	$this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
}