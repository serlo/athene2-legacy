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
namespace Taxonomy\Entity;

interface TermTaxonomyInterface
{

    public function getDescription();

    public function hasParent();

    public function hasChildren();

    public function setDescription($description);

    public function getTaxonomy();

    public function getChildren();

    public function getParent();

    public function getName();

    public function getSlug();

    public function setTaxonomy($taxonomy);

    public function setChildren($children);

    public function setParent($parent);

    public function getWeight();

    public function setWeight($weight);

    public function getTerm();

    public function setTerm($term);

    public function getArrayCopy();

    public function getAssociated($field);

    public function countAssociated($field);

    public function addAssociation($field, $entity);

    public function removeAssociation($field, $entity);

    public function getLanguage();

    /**
     *
     * @param string $association
     * @param int $of
     * @param int $order
     * @return object
     */
    public function orderAssociated($association, $of, $order);
}