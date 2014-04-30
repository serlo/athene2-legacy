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

interface TaxonomyTermAwareInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param TaxonomyTermInterface     $taxonomyTerm
     * @param TaxonomyTermNodeInterface $node
     * @return self
     */
    public function addTaxonomyTerm(TaxonomyTermInterface $taxonomyTerm, TaxonomyTermNodeInterface $node = null);

    /**
     * @param TaxonomyTermInterface     $taxonomyTerm
     * @param TaxonomyTermNodeInterface $node
     * @return self
     */
    public function removeTaxonomyTerm(TaxonomyTermInterface $taxonomyTerm, TaxonomyTermNodeInterface $node = null);

    /**
     * @return TaxonomyTermInterface[]
     */
    public function getTaxonomyTerms();
}
