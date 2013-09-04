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
use Core\Entity\AbstractEntity;
use Core\Entity\Language;
use Core\Entity\Subject;

/**
 * A role.
 * 
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role extends AbstractEntity implements RoleInterface
{
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

    public function __construct() {
    	$this->roleUsers = new ArrayCollection();
    }
}