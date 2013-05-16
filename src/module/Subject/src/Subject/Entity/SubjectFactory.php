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
namespace Subject\Entity;

use Core\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Subject Factory.
 *
 * @ORM\Entity
 * @ORM\Table(name="subject_factory")
 */
class SubjectFactory extends AbstractEntity
{
	/** @ORM\Column(type="text",length=255,name="class_name") */
    protected $name;
    
    /**
     * @ORM\OneToMany(targetEntity="Subject", mappedBy="factory")
     **/
    protected $subjects;

    public function __construct()
    {
        $this->subjects = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
	/**
     * @return field_type $name
     */
    public function getName ()
    {
        return $this->name;
    }

	/**
     * @return field_type $subjects
     */
    public function getSubjects ()
    {
        return $this->subjects;
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
     * @param field_type $subjects
     * @return $this
     */
    public function setSubjects ($subjects)
    {
        $this->subjects = $subjects;
        return $this;
    }

}