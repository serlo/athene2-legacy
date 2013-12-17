<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Service;

use Taxonomy\Model\TaxonomyTermModelInterface;
use Common\Normalize\Normalizable;
use Taxonomy\Manager\SharedTaxonomyManagerAwareInterface;
use Taxonomy\Manager\TaxonomyManagerInterface;
use Taxonomy\Collection\TermCollection;
use Taxonomy\Model\TaxonomyTermEntityAwareInterface;

interface TermServiceInterface //extends TaxonomyTermModelInterface, SharedTaxonomyManagerAwareInterface
{

    /**
     *
     * @return TaxonomyManagerInterface
     */
    public function getManager();

    /**
     *
     * @param TaxonomyTermModelInterface $term            
     * @return $this
     */
    public function setEntity(TaxonomyTermModelInterface $term);

    /**
     *
     * @param TaxonomyManagerInterface $taxonomyManager            
     * @return $this
     */
    public function setManager(TaxonomyManagerInterface $taxonomyManager);
    
    /**
     * <code>
     * $slugs = explode('path/to/slug', '/');
     * $term = $termService->getDescendantBySlugs($slugs);
     * </code>
     * 
     * @param array $slugs
     * @return self
     */
    public function getDescendantBySlugs(array $slugs);
    
    /**
     * 
     * @param array $names
     * @return TermCollection
     */
    public function findChildrenByTaxonomyNames(array $names);
    
    /**
     * 
     * @param string $name
     * @return string
     */
    public function getTemplate($name);
    
    /**
     * 
     * @param unknown $targetField
     * @param array $allowedTaxonomies
     * @return TaxonomyTermEntityAwareInterface[]
     */
    public function getAssociatedRecursive($targetField, array $allowedTaxonomies = array());
    
    /**
     * 
     * @param string $association
     * @return bool
     */
    public function isAssociationAllowed($association);
}