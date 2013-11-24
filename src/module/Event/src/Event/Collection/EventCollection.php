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
namespace Event\Collection;

use Common\Collection\AbstractDelegatorCollection;
use Event\Exception;
use Event\Entity\EventLogInterface;
use Event\EventManagerInterface;

class EventCollection extends AbstractDelegatorCollection
{

    /**
     *
     * @return EventManagerInterface
     */
    public function getManager()
    {
        return parent::getManager();
    }
    
    /*
     * (non-PHPdoc) @see \Common\Collection\AbstractDelegatorCollection::getFromManager()
     */
    public function getFromManager($key)
    {
        if (! $key instanceof EventLogInterface)
            throw new Exception\InvalidArgumentException(sprintf('`%s` does not implement `EventLogInterface`', get_class($key)));
        
        return $this->getManager()->getEvent($key->getId());
    }

    protected function validManager($manager)
    {
        if (! $manager instanceof EventManagerInterface)
            throw new Exception\InvalidArgumentException(sprintf('`%s` does not implement `EventManagerInterface`', get_class($manager)));
    }
}