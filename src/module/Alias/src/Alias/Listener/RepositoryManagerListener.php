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
use Common\Listener\AbstractSharedListenerAggregate;
use Entity\Entity\EntityInterface;
use Language\Manager\LanguageManagerAwareTrait;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;

class RepositoryManagerListener extends AbstractSharedListenerAggregate
{
    use AliasManagerAwareTrait, LanguageManagerAwareTrait;

    public function attachShared(SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'checkout', [$this, 'onCheckout']);

        return $this;
    }

    protected function getMonitoredClass()
    {
        return 'Versioning\RepositoryManager';
    }

    public function onCheckout(Event $e)
    {
        $entity = $e->getParam('repository');

        if ($entity instanceof EntityInterface && $entity->getType()->getName() == 'article') {
            $language = $this->getLanguageManager()->getLanguageFromRequest();

            $url = $this->getAliasManager()->getRouter()->assemble(
                ['entity' => $entity->getId()],
                ['name' => 'entity/page']
            );

            $this->getAliasManager()->autoAlias('entity', $url, $entity, $language);
        }
    }
}