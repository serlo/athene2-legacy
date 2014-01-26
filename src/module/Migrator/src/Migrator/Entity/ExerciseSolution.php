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
 * @ORM\Table(name="serlo_dev.exercise_solution_revisions")
 */
class ExerciseSolution
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;


    /**
     * @ORM\Column(type="string")
     */
    protected $hint;

    /**
     * @ORM\Column(type="string")
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="ExerciseTranslation", inversedBy="solution")
     * @ORM\JoinColumn(name="is_solution_id", referencedColumnName="id")
     */
    protected $exercise;

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return ExerciseSolution
     */
    public function getExercise()
    {
        return $this->exercise;
    }

    /**
     * @return mixed
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
