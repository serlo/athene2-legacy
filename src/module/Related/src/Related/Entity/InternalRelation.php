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
namespace Related\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="related_internal")
 */
class InternalRelation
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="externalRelations")
     */
    protected $relation;

    /**
     * @ORM\Column(type="string") *
     */
    protected $title;

    /**
     *
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return the $relation
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     *
     * @return the $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param field_type $relation            
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;
        return $this;
    }

    /**
     *
     * @param field_type $title            
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
}