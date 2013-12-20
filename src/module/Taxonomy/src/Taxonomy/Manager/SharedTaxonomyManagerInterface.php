<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Manager;

use Taxonomy\Service\TermServiceInterface;
use Language\Model\LanguageModelInterface;

interface SharedTaxonomyManagerInterface
{

    /**
     *
     * @param string $taxonomy            
     * @param LanguageModelInterface $language            
     * @return TaxonomyManagerInterface
     */
    public function findTaxonomyByName($taxonomy, LanguageModelInterface $language);

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
    public function getTerm($idOrObject);

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