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
namespace Entity\Entity;

use Uuid\Entity\UuidHolder;
use Language\Entity\LanguageAwareInterface;
use Versioning\Entity\RepositoryInterface;
use Link\Entity\LinkableInterface;
use License\Entity\LicenseAwareInterface;
use Taxonomy\Entity\TaxonomyTermAwareInterface;
use DateTime;
use Type\Entity\TypeAwareInterface;

interface EntityInterface extends UuidHolder, LanguageAwareInterface, RepositoryInterface, LinkableInterface, LicenseAwareInterface, TaxonomyTermAwareInterface, TypeAwareInterface
{

    /**
     *
     * @return DateTime
     */
    public function getTimestamp();

    /**
     *
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
    public function getChildren($linkyType, $childType = NULL);

    /**
     * Returns the parents
     *
     * @param string $linkyType            
     * @param string $parentType            
     * @return Collection
     */
    public function getParents($linkyType, $parentType = NULL);

    /**
     *
     * @return bool
     */
    public function isUnrevised();

    /**
     *
     * @return RevisionInterface
     */
    public function getHead();
}