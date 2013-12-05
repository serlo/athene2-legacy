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

interface TermServiceInterface extends Normalizable, TaxonomyTermModelInterface
{
    /**
     * 
     * @param TaxonomyTermModelInterface $term
     * @return $this
     */
    public function setEntity(TaxonomyTermModelInterface $term);
}