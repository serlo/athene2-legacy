<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Service;

use Taxonomy\Entity\TermTaxonomyInterface;
use Taxonomy\Manager\TaxonomyManagerInterface;

interface TermServiceInterface
{


    public function getAllowedParentTypeNames();
    
    public function getAllowedChildrenTypeNames();
    public function setTermTaxonomy(TermTaxonomyInterface $term);

    public function getTermTaxonomy();

    public function getDescendantBySlugs(array $path);

    public function findChildrenByTaxonomyName($taxonomy);

    public function getTemplate($template);

    public function hasChildren();
    public function hasParent();

    public function getParent();

    public function getChildren();
    
    public function filterChildren(array $types);

    public function getAllLinks();

    public function hasLinks($targetField);

    public function countLinks($targetField);

    public function getAssociated($targetField, $recursive = false, $allowedTaxonomies = NULL);

    public function getCallbackForLink($link);

    public function associate($targetField, $target);

    public function removeAssociation($targetField, $target);

    public function isAssociated($targetField, $target);

    public function getAllowedAssociations();

    public function isAssociationAllowed($targetField);

    public function knowsAncestor($ancestor);

    public function setName($name);

    public function childNodeAllowed(TermTaxonomyInterface $term);

    public function parentNodeAllowed(TermTaxonomyInterface $term);

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

    public function getTaxonomy();

    public function getLanguageService();

    public function getTypeName();

    public function getSlug();

    public function getManager();

    public function setManager(TaxonomyManagerInterface $termManager);
}