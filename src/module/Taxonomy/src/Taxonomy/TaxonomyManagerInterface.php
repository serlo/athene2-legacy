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
use Doctrine\Common\Collections\Criteria;

interface TaxonomyManagerInterface
{

    public function addTerm (TermServiceInterface $ts);

    public function createTerm ();

    public function hasTerm ($val);

    /**
     *
     * @param
     *            int|array an id or a path
     */
    public function getTerm ($id);

    public function getTerms (Criteria $filter = NULL);

    public function toArray ();

    public function setTemplate ($template);

    public function setTermTemplate ($template);

    public function enableLink ($targetField,\Closure $callback);

    public function linkingAllowed ($targetField);
}