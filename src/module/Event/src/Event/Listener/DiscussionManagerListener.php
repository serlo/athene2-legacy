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

use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;

/**
 * Event Listener for Discussion\Controller\DiscussionController
 */
class DiscussionManagerListener extends AbstractListener
{

    public function attachShared(SharedEventManagerInterface $events)
    {
        $class = $this->getMonitoredClass();
        $events->attach($class, 'start', [$this, 'onStart']);
        $events->attach($class, 'comment', [$this, 'onComment']);
        $events->attach($class, 'archive', [$this, 'onArchive']);
        $events->attach($class, 'restore', [$this, 'onRestore']);
    }

    protected function getMonitoredClass()
    {
        return 'Discussion\DiscussionManager';
    }

    /**
     * Gets executed on 'archive'
     *
     * @param Event $e
     * @return void
     */
    public function onArchive(Event $e)
    {
        $discussion = $e->getParam('discussion');
        $instance   = $discussion->getInstance();
        $this->logEvent('discussion/comment/archive', $instance, $discussion);
    }

    /**
     * Gets executed on 'comment'
     *
     * @param Event $e
     * @return void
     */
    public function onComment(Event $e)
    {
        $instance   = $e->getParam('instance');
        $discussion = $e->getParam('discussion');
        $comment    = $e->getParam('comment');
        $params     = [
            [
                'name'  => 'discussion',
                'value' => $discussion
            ]
        ];
        $this->logEvent('discussion/comment/create', $instance, $comment, $params);
    }

    /**
     * Gets executed on 'restore'
     *
     * @param Event $e
     * @return void
     */
    public function onRestore(Event $e)
    {
        $discussion = $e->getParam('discussion');
        $instance   = $discussion->getInstance();
        $this->logEvent('discussion/restore', $instance, $discussion);
    }

    /**
     * Gets executed on 'start'
     *
     * @param Event $e
     * @return void
     */
    public function onStart(Event $e)
    {
        $instance   = $e->getParam('instance');
        $discussion = $e->getParam('discussion');
        $params     = [
            [
                'name'  => 'on',
                'value' => $e->getParam('on')
            ]
        ];

        $this->logEvent('discussion/create', $instance, $discussion, $params);
    }
}