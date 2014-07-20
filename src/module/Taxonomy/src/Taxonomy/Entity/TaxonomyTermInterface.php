<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Taxonomy\Entity;

use Doctrine\Common\Collections\Collection;
use Instance\Entity\InstanceProviderInterface;
use Type\Entity\TypeInterface;
use Uuid\Entity\UuidInterface;

interface TaxonomyTermInterface extends InstanceProviderInterface, UuidInterface
{
    /**
     * @param TaxonomyTermAwareInterface $object
     * @return self
     */
    public function associateObject(TaxonomyTermAwareInterface $object);

    /**
     * @param string $association
     * @return int
     */
    public function countAssociations($association);

    /**
     * @return int
     */
    public function countElements();

    /**
     * @param string $name
     * @return TaxonomyTermInterface
     */
    public function findAncestorByTypeName($name);

    /**
     * @param array|string[] $names
     * @return self[]|Collection
     */
    public function findChildrenByTaxonomyNames(array $names);

    /**
     * @param string $association
     * @return TaxonomyTermAwareInterface[]|Collection
     */
    public function getAssociated($association);

    /**
     * @param string $association
     * @param array  $allowedTaxonomies
     * @return TaxonomyTermAwareInterface[]|Collection
     */
    public function getAssociatedRecursive($association, array $allowedTaxonomies = []);

    /**
     * @return Collection|TaxonomyTermInterface[]
     */
    public function getChildren();

    /**
     * @param bool $trashed
     * @return Collection|TaxonomyTermInterface[]
     */
    public function findChildrenByTrashed($trashed);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return self
     */
    public function getParent();

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @return TaxonomyInterface
     */
    public function getTaxonomy();

    /**
     * @return TypeInterface
     */
    public function getType();

    /**
     * @return bool
     */
    public function hasChildren();

    /**
     * @return bool
     */
    public function hasParent();

    /**
     * @param TaxonomyTermAwareInterface $object
     * @return bool
     */
    public function isAssociated(TaxonomyTermAwareInterface $object);

    /**
     * @param self $ancestor
     * @return bool
     */
    public function knowsAncestor(self $ancestor);

    /**
     * @param TaxonomyTermAwareInterface|int $object
     * @param int                            $position
     * @param string                         $association
     * @return self
     */
    public function positionAssociatedObject($object, $position, $association = null);

    /**
     * @param TaxonomyTermAwareInterface $object
     * @return self
     */
    public function removeAssociation(TaxonomyTermAwareInterface $object);

    /**
     * @param string $description
     * @return self
     */
    public function setDescription($description);

    /**
     * @param self $parent
     * @return self
     */
    public function setParent(self $parent = null);

    /**
     * @param int $position
     * @return self
     */
    public function setPosition($position);

    /**
     * @param TaxonomyInterface $taxonomy
     * @return self
     */
    public function setTaxonomy(TaxonomyInterface $taxonomy);
}
