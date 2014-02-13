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

use Zend\EventManager\Event;

class PageControllerListener extends AbstractListener
{

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $events->attach($this->getMonitoredClass(), 'page.create', array($this, 'onUpdate'));
    }

    protected function getMonitoredClass()
    {
        return 'Page\Controller\IndexController';
    }

    /**
     * Gets executed on page create
     *
     * @param Event $e
     * @return void
     */
    public function onUpdate(Event $e)
    {
        $slug       = $e->getParam('slug');
        $repository = $e->getParam('repository');
        $instance   = $e->getParam('instance');

        $url = $e->getTarget()->url()->fromRoute(
            'page/view',
            array(
                'page' => $repository->getId()
            )
        );

        $this->getAliasManager()->createAlias(
            $url,
            $slug,
            $slug . '-' . $repository->getId(),
            $repository->getUuidEntity(),
            $instance
        );
    }
}