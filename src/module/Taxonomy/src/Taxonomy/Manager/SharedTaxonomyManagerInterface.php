<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Manager;

use Language\Service\LanguageServiceInterface;
use Taxonomy\Entity\TermTaxonomyInterface;
use Taxonomy\Service\TermServiceInterface;

interface SharedTaxonomyManagerInterface
{

    /**
     * 
     * @param TaxonomyManagerInterface $termManager
     * @return $this
     */
    public function addTaxonomy(TaxonomyManagerInterface $termManager);

    /**
     *
     * @param string $taxonomy            
     * @param LanguageServiceInterface $language            
     * @return TaxonomyManagerInterface
     */
    public function getTaxonomy($taxonomy,  $language = NULL);

    /**
     *
     * @param TermTaxonomyInterface|numeric $term            
     * @return TermServiceInterface
     */
    public function getTermService($term);

    /**
     *
     * @param numeric $id          
     * @return $this
     */
    public function hasTaxonomy($id);

    /**
     *
     * @param int|TermTaxonomyInterface $term            
     * @return $this
     */
    public function deleteTerm($term);

    /**
     *
     * @param unknown $link            
     */
    public function getCallback($link);

    /**
     *
     * @param unknown $type            
     */
    public function getAllowedChildrenTypes($type);
}