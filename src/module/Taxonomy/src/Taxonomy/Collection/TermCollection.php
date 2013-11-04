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
namespace Taxonomy\Collection;

use Common\Collection\AbstractDelegatorCollection;
use Taxonomy\Manager\SharedTaxonomyManagerInterface;
use Taxonomy\Exception\InvalidArgumentException;

class TermCollection extends AbstractDelegatorCollection
{
    
    /*
     * (non-PHPdoc) @see \Common\Collection\AbstractDelegatorCollection::getDelegate()
     */
    public function getDelegate($delegator)
    {
        return $delegator->getEntity();
    }
    
    /*
     * (non-PHPdoc) @see \Common\Collection\AbstractDelegatorCollection::getFromManager()
     */
    public function getFromManager($key)
    {
        $term = $this->getManager()->getTerm($key);
        return $term;
    }

    protected function validManager($manager)
    {
        if (! $manager instanceof SharedTaxonomyManagerInterface)
            throw new InvalidArgumentException(sprintf('`%s` does not implement `SharedTaxonomyManagerInterface`', get_class($manager)));
    }

    /**
     *
     * @return SharedTaxonomyManagerInterface
     */
    public function getManager()
    {
        return parent::getManager();
    }
}