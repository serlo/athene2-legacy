<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Event\Listener;

use Instance\Entity\InstanceProviderInterface;
use Uuid\Entity\UuidInterface;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;

class LicenseManagerListener extends AbstractListener
{
    public function attachShared(SharedEventManagerInterface $events)
    {
        $events->attach($this->getMonitoredClass(), 'inject', [$this, 'onInject']);
    }

    protected function getMonitoredClass()
    {
        return 'License\Manager\LicenseManager';
    }

    public function onInject(Event $e)
    {
        $object = $e->getParam('object');
        if ($object instanceof InstanceProviderInterface && $object instanceof UuidInterface) {
            $this->logEvent('license/object/set', $object->getInstance(), $object);
        }
    }
}
