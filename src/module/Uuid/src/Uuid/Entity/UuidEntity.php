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
namespace Uuid\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class UuidEntity implements UuidHolder
{
    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Uuid\Entity\Uuid")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;
    
    public function __construct($uuid = NULL){
        if($uuid){
            $this->id = $uuid;
        }
    }
    
    public function getUuid ()
    {
        return $this->_getUuid()->getUuid();
    }
    
    public function setId ($id)
    {
        return $this->setUuid($id);
    }
    
    
    public function getId ()
    {
        return $this->_getUuid()->getId();
    }
    
    public function setUuid (UuidInterface $uuid = null)
    {
        $this->id = $uuid;
        return $this;
    }
    
    protected function _getUuid(){
        return $this->id;
    }
}