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

interface TermTaxonomyEntityInterface
{
    public function getParent();
    public function hasParent();
    public function getChildren();
    public function getTerm();
    public function getDescription();
    public function setDescription($description);
    public function setOrder($order);
    public function getOrder($order);
}