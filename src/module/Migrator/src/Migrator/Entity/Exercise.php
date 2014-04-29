<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Migrator\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * An entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="serlo_dev.exercises")
 */
class Exercise
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="license_id")
     */
    protected $license;
    /**
     * @ORM\Column(type="string", name="is_group")
     */
    protected $group;

    /**
     * @ORM\OneToMany(targetEntity="ExerciseGroup", mappedBy="exercise")
     * @ORM\OrderBy({"chronology" = "ASC"})
     **/
    protected $parents;

    /**
     * @ORM\OneToMany(targetEntity="ExerciseGroup", mappedBy="group")
     * @ORM\OrderBy({"chronology" = "ASC"})
     **/
    protected $children;

    /**
     * @ORM\OneToMany(targetEntity="ExerciseTranslation", mappedBy="exercise")
     */
    protected $translations;

    /**
     * @ORM\OneToMany(targetEntity="ExerciseFolder", mappedBy="exercise")
     */
    protected $folders;

    public function __construct()
    {
        $this->folders      = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->children     = new ArrayCollection();
        $this->parents      = new ArrayCollection();
    }

    /**
     * @return self[]
     */
    public function getChildren()
    {
        $return = new ArrayCollection();
        foreach ($this->children as $child) {
            $return->add($child->getExercise());
        }
        return $return;
    }

    /**
     * @return ExerciseFolder[]
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @return self[]
     */
    public function getParents()
    {
        $return = new ArrayCollection();
        foreach ($this->parents as $parent) {
            $return->add($parent->getGroup());
        }
        return $return;
    }

    /**
     * @return ExerciseTranslation[]
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @return mixed
     */
    public function isGroup()
    {
        return $this->group;
    }
}
