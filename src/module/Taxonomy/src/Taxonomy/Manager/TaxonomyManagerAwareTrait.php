<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Taxonomy\Manager;

use Taxonomy\Manager\TaxonomyManagerInterface;

trait TaxonomyManagerAwareTrait
{

    /**
     *
     * @var TaxonomyManagerInterface
     */
    protected $taxonomyManager;

    /**
     *
     * @return TaxonomyManagerInterface
     *         $termManager
     */
    public function getTaxonomyManager ()
    {
        return $this->taxonomyManager;
    }

    /**
     *
     * @param TaxonomyManagerInterface $termManager            
     * @return self
     */
    public function setTaxonomyManager (TaxonomyManagerInterface $termManager)
    {
        $this->taxonomyManager = $termManager;
        return $this;
    }
}
