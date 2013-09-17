<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * A role.
 * 
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role implements RoleInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string") *
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", nullable=true) *
     */
    protected $description;
    
    /**
     * @ORM\OneToMany(targetEntity="RoleUser", mappedBy="role")
     **/
    private $roleUsers;

    /**
     * @return field_type $id
     */
    public function getId ()
    {
        return $this->id;
    }

	/**
     * @param field_type $id
     * @return $this
     */
    public function setId ($id)
    {
        $this->id = $id;
        return $this;
    }

	/**
     * @return field_type $name
     */
    public function getName ()
    {
        return $this->name;
    }

	/**
     * @return field_type $description
     */
    public function getDescription ()
    {
        return $this->description;
    }

	/**
     * @return \Doctrine\Common\Collections\ArrayCollection $roleUsers
     */
    public function getRoleUsers ()
    {
        return $this->roleUsers;
    }
    
    public function getUsers($languageId) {
        $criteria = $languageId ? Criteria::create(Criteria::expr()->eq('language', $languageId)) : Criteria::create(Criteria::expr()->isNull('language'));
        $mn = $this->getRoleUsers()->matching($criteria);
        $collection = new ArrayCollection();
        foreach($mn as $key => $m){
            $collection->set($key, $m->getUser());
        }
        return $collection;
    }
    

	/**
     * @param field_type $name
     * @return $this
     */
    public function setName ($name)
    {
        $this->name = $name;
        return $this;
    }

	/**
     * @param field_type $description
     * @return $this
     */
    public function setDescription ($description)
    {
        $this->description = $description;
        return $this;
    }

	/**
     * @param \Doctrine\Common\Collections\ArrayCollection $roleUsers
     * @return $this
     */
    public function setRoleUsers ($roleUsers)
    {
        $this->roleUsers = $roleUsers;
        return $this;
    }

	public function __construct() {
    	$this->roleUsers = new ArrayCollection();
    }
}