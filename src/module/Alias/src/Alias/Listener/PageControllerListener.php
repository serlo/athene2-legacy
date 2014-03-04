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

use Page\Entity\PageRepositoryInterface;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;

class PageControllerListener extends AbstractListener
{

    public function attachShared(SharedEventManagerInterface $events)
    {
        $events->attach($this->getMonitoredClass(), 'page.create.postFlush', [$this, 'onUpdate']);
    }

    /**
     * Gets executed on page create
     *
     * @param Event $e
     * @return void
     */
    public function onUpdate(Event $e)
    {
        /* @var $repository PageRepositoryInterface */
        $slug       = $e->getParam('slug');
        $repository = $e->getParam('repository');
        $url        = $e->getTarget()->url()->fromRoute('page/view', ['page' => $repository->getId()]);
        $alias      = $this->getAliasManager()->createAlias(
            $url,
            $slug,
            $slug . '-' . $repository->getId(),
            $repository,
            $repository->getInstance()
        );
        $this->getAliasManager()->flush($alias);
    }

    protected function getMonitoredClass()
    {
        return 'Page\Controller\IndexController';
    }
}
