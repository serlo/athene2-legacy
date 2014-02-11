<?php
/**
 *
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Term\Manager;

use Instance\Entity\InstanceInterface;
use Term\Entity\TaxonomyTermInterface;

interface TermManagerInterface
{

    /**

     * @param string $name            
     * @param string $slug            
     * @param InstanceInterface $instance
     * @return TaxonomyTermInterface
     */
    public function createTerm($name, $slug = NULL, InstanceInterface $instance);

    /**
     *
     * @param TaxonomyTermInterface|int|string $term            
     * @return TaxonomyTermInterface
     */
    public function getTerm($term);

    /**

     * @param unknown $name            
     * @param InstanceInterface $instance
     * @return TaxonomyTermInterface
     */
    public function findTermByName($name, InstanceInterface $instance);

    /**

     * @param unknown $slug            
     * @param InstanceInterface $instance
     * @return TaxonomyTermInterface
     */
    public function findTermBySlug($slug, InstanceInterface $instance);
}