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

interface TaxonomyTermNodeInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $position
     * @return self
     */
    public function setPosition($position);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param TaxonomyTermInterface $taxonomyTerm
     * @return self
     */
    public function setTaxonomyTerm(TaxonomyTermInterface $taxonomyTerm);

    /**
     * @return TaxonomyTermInterface
     */
    public function getTaxonomyTerm();

    /**
     * @param TaxonomyTermAwareInterface $object
     * @return self
     */
    public function setObject(TaxonomyTermAwareInterface $object);

    /**
     * @return TaxonomyTermAwareInterface
     */
    public function getObject();
}