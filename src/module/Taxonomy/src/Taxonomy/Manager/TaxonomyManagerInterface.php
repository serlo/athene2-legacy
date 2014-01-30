<?php
/**
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Taxonomy\Manager;

use Common\ObjectManager\Flushable;
use Instance\Entity\InstanceInterface;
use Taxonomy\Entity\TaxonomyInterface;
use Taxonomy\Entity\TaxonomyTermAwareInterface;
use Taxonomy\Entity\TaxonomyTermInterface;

interface TaxonomyManagerInterface extends Flushable
{

    /**
     *
     * @param numeric $id            
     * @return TaxonomyTermInterface
     */
    public function getTerm($id);

    /**
     *
     * @param TaxonomyInterface $taxonomy            
     * @param array $ancestors            
     * @return TaxonomyTermInterface
     */
    public function findTerm(TaxonomyInterface $taxonomy, array $ancestors);

    /**
     *
     * @param numeric $id            
     * @return TaxonomyInterface
     */
    public function getTaxonomy($id);

    /**

     * @param string $name            
     * @param InstanceInterface $instance
     * @return TaxonomyInterface
     */
    public function findTaxonomyByName($name, InstanceInterface $instance);

    /**

     * @param array $data            
     * @param InstanceInterface $instance
     * @return TaxonomyTermInterface
     */
    public function createTerm(array $data, InstanceInterface $instance);

    /**
     *
     * @param int $id            
     * @param array $data            
     * @return self
     */
    public function updateTerm($id, array $data);

    /**
     *
     * @param int $id
     * @param string $association
     * @param TaxonomyTermAwareInterface $with
     * @return self
     */
    public function associateWith($id, $association, TaxonomyTermAwareInterface $with);

    /**
     *
     * @param int $id
     * @param string $association
     * @param TaxonomyTermAwareInterface $object
     * @return self
     */
    public function removeAssociation($id, $association, TaxonomyTermAwareInterface $object);
}