<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Subject\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Entity\Entity\EntityInterface;
use Instance\Entity\InstanceInterface;
use Taxonomy\Entity\TaxonomyTermInterface;

interface SubjectManagerInterface
{

    /**
     * @param string            $name
     * @param InstanceInterface $instance
     * @return TaxonomyTermInterface
     */
    public function findSubjectByString($name, InstanceInterface $instance);

    /**
     * @param InstanceInterface $instance
     * @return ArrayCollection|TaxonomyTermInterface[]
     */
    public function findSubjectsByInstance(InstanceInterface $instance);

    /**
     * @param int $id
     * @return TaxonomyTermInterface
     */
    public function getSubject($id);

    /**
     * @param TaxonomyTermInterface $subject
     * @return EntityInterface[]
     */
    public function getUnrevisedEntities(TaxonomyTermInterface $subject);

    /**
     * @param TaxonomyTermInterface $subject
     * @return EntityInterface[]
     */
    public function getTrashedEntities(TaxonomyTermInterface $subject);
}