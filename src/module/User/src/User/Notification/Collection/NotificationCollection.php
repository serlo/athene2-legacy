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
namespace User\Notification\Collection;

use Common\Collection\AbstractDelegatorCollection;
use User\Notification;
use User\Exception;

class NotificationCollection extends AbstractDelegatorCollection
{
	/* (non-PHPdoc)
     * @see \Common\Collection\AbstractDelegatorCollection::getDelegate()
     */
    public function getDelegate ($delegator)
    {
        return $delegator->getNotification();
    }

    /**
     * (non-PHPdoc)
     * @return UserManagerInterface
     */
    public function getFromManager ($key)
    {
        return $this->getManager()->getNotificationService($key);
    }
    
    protected function validManager($manager){
        if(!$manager instanceof Notification\NotificationManagerInterface)
            throw new Exception\InvalidArgumentException(sprintf('`%s` does not implement `NotificationManagerInterface`', get_class($manager)));
    }
}