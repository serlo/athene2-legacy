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
class SubjectFactory extends AbstractEntity implements TermEntityInterface
{
    /**
     * @ORM\Column(type="text",length=255)
     */
    protected $name;
    
    /**
     * @ORM\OneToMany(targetEntity="Subject", mappedBy="factory")
     **/
    protected $subjects;
}