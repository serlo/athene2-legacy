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
namespace Entity\Collection;

use Entity\Manager\EntityManagerInterface;
use Common\Collection\AbstractDelegatorCollection;
use Entity\Exception\InvalidArgumentException;

class EntityCollection extends AbstractDelegatorCollection
{
    
	/* (non-PHPdoc)
     * @see \Common\Collection\AbstractDelegatorCollection::getDelegate()
     */
    public function getDelegate ($component)
    {
        return $component->getEntity();
    }

	/* (non-PHPdoc)
     * @see \Common\Collection\AbstractDelegatorCollection::getFromManager()
     */
    public function getFromManager ($key)
    {
        return $this->getManager()->get($key);
    }
    
    protected function validManager($manager){
        if(!$manager instanceof EntityManagerInterface)
            throw new InvalidArgumentException(sprintf('`%s` does not implement `EntityManagerInterface`', get_class($manager)));
    }
}