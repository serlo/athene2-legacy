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
use Language\Model\LanguageModelAwareInterface;
use Versioning\Entity\RepositoryInterface;
use Link\Entity\LinkableInterface;
use License\Entity\LicenseAwareInterface;
use Taxonomy\Model\TaxonomyTermEntityAwareInterface;
use DateTime;
use Type\Entity\TypeAwareInterface;
use Entity\Options\EntityOptions;

interface EntityInterface extends UuidHolder, LanguageModelAwareInterface, RepositoryInterface, LinkableInterface, LicenseAwareInterface, TaxonomyTermEntityAwareInterface, TypeAwareInterface
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
     *
     * @param EntityOptions $options            
     * @return self
     */
    public function setOptions(EntityOptions $options);

    /**
     *
     * @return EntityOptions
     */
    public function getOptions();

    /**
     * Returns the children
     *
     * @param string $type            
     * @return Collection
     */
    public function getChildren($typeName);

    /**
     * Returns the parents
     *
     * @param string $type            
     * @return Collection
     */
    public function getParents($typeName);
}