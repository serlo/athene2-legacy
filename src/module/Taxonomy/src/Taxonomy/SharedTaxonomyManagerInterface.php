<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy;

use Taxonomy\Service\TermServiceInterface;

interface SharedTaxonomyManagerInterface
{
    /**
     * 
     * @param unknown $name
     * @return TaxonomyManagerInterface
     */
    public function get ($taxonomy);

    /**
     *
     * @param TermManagerInterface
     * @return $this
     */
    public function add(TermManagerInterface $manager);
    
    /**
     * returns a term by it's unique id
     * 
     * @return TermServiceInterface
     */
    public function getTerm($id);
}