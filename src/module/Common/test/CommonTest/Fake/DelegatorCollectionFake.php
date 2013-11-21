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
namespace CommonTest\Fake;

use Common\Collection\AbstractDelegatorCollection;

class DelegatorCollectionFake extends AbstractDelegatorCollection
{
    /*
     * (non-PHPdoc) @see \Common\Collection\AbstractDelegatorCollection::getDelegate()
     */
    public function getDelegate($delegator)
    {
        return $delegator;
    }
    
    /*
     * (non-PHPdoc) @see \Common\Collection\AbstractDelegatorCollection::getFromManager()
     */
    public function getFromManager($key)
    {
        return $this->getManager()->get();
    }
}