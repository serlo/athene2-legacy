<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace RelatedContent\Entity;

use Doctrine\Common\Collections\Collection;
use Uuid\Entity\UuidInterface;

interface ContainerInterface
{
    /**
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @return Collection
     */
    public function getHolders();

    /**
     *
     * @param HolderInterface $holder
     * @return self
     */
    public function addHolder(HolderInterface $holder);

    /**
     * @return UuidInterface
     */
    public function getObject();
}
