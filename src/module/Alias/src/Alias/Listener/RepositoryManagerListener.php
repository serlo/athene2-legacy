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
namespace Alias\Listener;

use Alias\AliasManagerAwareTrait;
use Entity\Entity\EntityInterface;
use Instance\Manager\InstanceManagerAwareTrait;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;

class RepositoryManagerListener extends AbstractListener
{
    public function attachShared(SharedEventManagerInterface $events)
    {
        $events->attach($this->getMonitoredClass(), 'checkout', [$this, 'onCheckout']);
    }

    protected function getMonitoredClass()
    {
        return 'Versioning\RepositoryManager';
    }

    public function onCheckout(Event $e)
    {
        $entity = $e->getParam('repository');

        if ($entity instanceof EntityInterface) {
            $instance = $entity->getInstance();

            if ($entity->getId() === null) {
                $this->getAliasManager()->flush($entity);
            }

            $url = $this->getAliasManager()->getRouter()->assemble(
                ['entity' => $entity->getId()],
                ['name' => 'entity/page']
            );

            $this->getAliasManager()->autoAlias('entity', $url, $entity, $instance);
        }
    }
}
