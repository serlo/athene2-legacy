<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Manager;

interface SharedTaxonomyManagerInterface
{

    public function add(TermManagerInterface $termManager);

    /**
     * 
     * @param unknown $taxonomy
     * @param string $language
     * @return TermManagerInterface
     */
    public function get($taxonomy, $language = NULL);

    public function getTerm($element);

    public function has($entity);

    public function deleteTerm($id);

    public function getCallback($link);

    public function getAllowedChildrenTypes($type);
}