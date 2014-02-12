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
use Zend\EventManager\SharedEventManagerInterface;

class BlogManagerListener extends AbstractListener
{
    /**
     * Gets executed on post create & update
     *
     * @param Event $e
     * @return void
     */
    public function onUpdate(Event $e)
    {
        /* @var $post \Blog\Entity\PostInterface */
        $post     = $e->getParam('post');
        $instance = $post->getInstance();

        $url = $this->getAliasManager()->getRouter()->assemble(
            ['post' => $post->getId()],
            ['name' => 'blog/post/view']
        );

        $this->getAliasManager()->autoAlias('blogPost', $url, $post, $instance);
    }

    public function attachShared(SharedEventManagerInterface $events)
    {
        $events->attach(
            $this->getMonitoredClass(),
            'create',
            array(
                $this,
                'onUpdate'
            )
        );

        $events->attach(
            $this->getMonitoredClass(),
            'update',
            array(
                $this,
                'onUpdate'
            )
        );
    }

    protected function getMonitoredClass()
    {
        return 'Blog\Manager\BlogManager';
    }
}