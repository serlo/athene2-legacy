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


use Doctrine\ORM\Mapping as ORM;

/**
 * An entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="serlo_dev.exercise_groups")
 */
class ExerciseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Exercise", inversedBy="children")
     * @ORM\JoinColumn(name="exercise_group_id", referencedColumnName="id", nullable=false)
     */
    protected $group;

    /**
     * @ORM\ManyToOne(targetEntity="Exercise", inversedBy="parents")
     * @ORM\JoinColumn(name="exercise_id", referencedColumnName="id", nullable=false)
     */
    protected $exercise;

    /**
     * @ORM\Column(type="integer")
     */
    protected $chronology;

    /**
     * @return mixed
     */
    public function getExercise()
    {
        return $this->exercise;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
 