<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy;

interface SharedTaxonomyManagerInterface
{
    /**
     * 
     * @param unknown $name
     * @param mixed $languageService
     * @return TaxonomyManagerInterface
     */
    public function get ($taxonomy, $languageService = NULL);

    /**
     *
     * @param TermManagerInterface
     * @return $this
     */
    public function add(TermManagerInterface $manager);
}