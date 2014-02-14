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
namespace Entity\Entity;

use DateTime;
use Instance\Entity\InstanceAwareInterface;
use License\Entity\LicenseAwareInterface;
use Link\Entity\LinkableInterface;
use Taxonomy\Entity\TaxonomyTermAwareInterface;
use Type\Entity\TypeAwareInterface;
use Uuid\Entity\UuidInterface;
use Versioning\Entity\RepositoryInterface;

interface EntityInterface
    extends UuidInterface, InstanceAwareInterface, RepositoryInterface, LinkableInterface, LicenseAwareInterface,
            TaxonomyTermAwareInterface, TypeAwareInterface
{

    /**
     * @return DateTime
     */
    public function getTimestamp();

    /**
     * @param DateTime $date
     */
    public function setTimestamp(DateTime $date);

    /**
     * Returns the children
     *
     * @param string $linkyType
     * @param string $childType
     * @return Collection
     */
    public function getChildren($linkyType, $childType = null);

    /**
     * Returns the parents
     *
     * @param string $linkyType
     * @param string $parentType
     * @return Collection
     */
    public function getParents($linkyType, $parentType = null);

    /**
     * @return bool
     */
    public function isUnrevised();

    /**
     * @return RevisionInterface
     */
    public function getHead();
}