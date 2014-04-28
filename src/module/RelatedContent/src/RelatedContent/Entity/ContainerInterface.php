<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace RelatedContent\Entity;

use Doctrine\Common\Collections\Collection;
use Instance\Entity\InstanceAwareInterface;
use Uuid\Entity\UuidInterface;

interface ContainerInterface extends InstanceAwareInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return Collection
     */
    public function getHolders();

    /**
     * @param HolderInterface $holder
     * @return self
     */
    public function addHolder(HolderInterface $holder);

    /**
     * @return UuidInterface
     */
    public function getObject();
}
