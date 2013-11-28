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
use Taxonomy\Service\TermServiceInterface;

interface SharedTaxonomyManagerInterface
{

    /**
     *
     * @param string $taxonomy            
     * @param LanguageServiceInterface $language            
     * @return TaxonomyManagerInterface
     */
    public function findTaxonomyByName($taxonomy, LanguageServiceInterface $language);

    /**
     *
     * @param numeric $id            
     * @return TaxonomyManagerInterface
     */
    public function getTaxonomy($id);

    /**
     *
     * @param TermTaxonomyInterface|numeric $term            
     * @return TermServiceInterface
     */
    public function getTerm($term);

    /**
     *
     * @param unknown $link            
     */
    public function getCallback($link);

    /**
     *
     * @param unknown $type            
     */
    public function getAllowedChildrenTypeNames($type);

    /**
     *
     * @param int $id            
     * @return $this
     */
    public function deleteTerm($id);

    /**
     *
     * @param array $data                           
     * @return $this
     */
    public function createTerm(array $data);

    /**
     *
     * @param int $id            
     * @param array $data            
     * @return $this
     */
    public function updateTerm($id, array $data);
}