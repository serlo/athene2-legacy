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
namespace Notification\Listener;

use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;

class DiscussionManagerListener extends AbstractListener
{
    public function attachShared(SharedEventManagerInterface $events)
    {
        $events->attach(
            $this->getMonitoredClass(),
            'start',
            array(
                $this,
                'onStartSubscribe'
            ),
            2
        );

        $events->attach(
            $this->getMonitoredClass(),
            'comment',
            array(
                $this,
                'onCommentSubscribe'
            ),
            2
        );
    }

    protected function getMonitoredClass()
    {
        return 'Discussion\DiscussionManager';
    }

    public function onCommentSubscribe(Event $e)
    {
        if (isset($e->getParam('data')['subscription'])) {
            $params = $e->getParam('data');
            $param  = $params['subscription'];
            if ($param['subscribe'] === '1') {
                $user          = $e->getParam('author');
                $discussion    = $e->getParam('discussion')->getUuidEntity();
                $comment       = $e->getParam('comment')->getUuidEntity();
                $notifyMailman = $param['mailman'] === '1' ? true : false;

                $this->subscribe($user, $discussion, $notifyMailman); // We want to subscribe to the discussion
                $this->subscribe(
                    $user,
                    $comment,
                    $notifyMailman
                ); // And also to the comment for e.g. listening on likes
            }
        }
    }

    public function onStartSubscribe(Event $e)
    {
        if (isset($e->getParam('data')['subscription'])) {
            $params = $e->getParam('data');
            $param  = $params['subscription'];
            if ($param['subscribe'] === '1') {
                $user          = $e->getParam('author');
                $discussion    = $e->getParam('discussion')->getUuidEntity();
                $notifyMailman = $param['mailman'] === '1' ? true : false;
                $this->subscribe($user, $discussion, $notifyMailman);
            }
        }
    }
}
