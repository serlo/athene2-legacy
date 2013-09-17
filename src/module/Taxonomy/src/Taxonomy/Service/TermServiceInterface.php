<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Service;

use Taxonomy\Entity\TermTaxonomyEntityInterface;
use Taxonomy\Manager\TermManagerInterface;

interface TermServiceInterface
{

    public function getDescendantBySlugs(array $path);

    public function getChildrenByTaxonomyName($taxonomy);

    public function getTemplate($template);

    public function getManager();

    public function setManager(TermManagerInterface $termManager);

    public function hasChildren();

    public function getParent();

    public function getChildren();

    public function getAllLinks();

    public function hasLinks($targetField);

    public function countLinks($targetField);

    public function getLinks($targetField, $recursive = false, $allowedTaxonomies = NULL);

    public function getCallbackForLink($link);

    public function addLink($targetField, $target);

    public function removeLink($targetField, $target);

    public function hasLink($targetField, $target);

    public function getAllowedLinks();

    public function isLinkAllowed($targetField);

    public function update(array $data);

    public function knowsAncestor($ancestor);

    public function setName($name);

    public function childNodeAllowed(TermTaxonomyEntityInterface $term);

    public function parentNodeAllowed(TermTaxonomyEntityInterface $term);

    public function allowsParentType($type);

    public function allowsChildType($type);

    public function getAllowedParentTypes();

    public function getAllowedChildrenTypes();

    public function radixEnabled();

    public function setParent($parent);

    public function getConfig();

    public function getOption($name);

    public function getId();

    public function getName();

    public function getType();

    public function getTaxonomy();

    public function getTypeName();

    public function getSlug();

    public function getArrayCopy();
}