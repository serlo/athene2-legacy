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
namespace User\Notification\Listener;

use Zend\EventManager\Event;

class DiscussionControllerListener extends AbstractListener
{

    /**
     *
     * @var array
     */
    protected $listeners = array();

    public function onStartSubscribe(Event $e)
    {
        if (array_key_exists('subscription', $e->getParam('post'))) {
            $params = $e->getParam('post');
            $param = $params['subscription'];
            if ($param['subscribe'] === '1') {
                $user = $e->getParam('user');
                $discussion = $e->getParam('discussion')
                    ->getEntity()
                    ->getUuidEntity();
                $notifyMailman = $param['mailman'] === '1' ? true : false;
                $this->subscribe($user, $discussion, $notifyMailman);
            }
        }
    }

    public function onCommentSubscribe(Event $e)
    {
        if (array_key_exists('subscription', $e->getParam('post'))) {
            $params = $e->getParam('post');
            $param = $params['subscription'];
            if ($param['subscribe'] === '1') {
                $user = $e->getParam('user');
                $discussion = $e->getParam('discussion')
                    ->getEntity()
                    ->getUuidEntity();
                $comment = $e->getParam('comment')
                    ->getEntity()
                    ->getUuidEntity();
                $notifyMailman = $param['mailman'] === '1' ? true : false;
                
                $this->subscribe($user, $discussion, $notifyMailman); // We want to subscribe to the discussion
                $this->subscribe($user, $comment, $notifyMailman); // And also to the comment for e.g. listening on likes
            }
        }
    }
    
    /*
     * (non-PHPdoc) @see \Zend\EventManager\SharedListenerAggregateInterface::attachShared()
     */
    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('Discussion\Controller\DiscussionController', 'start', array(
            $this,
            'onStartSubscribe'
        ), 2);
        
        $this->listeners[] = $events->attach('Discussion\Controller\DiscussionController', 'comment', array(
            $this,
            'onCommentSubscribe'
        ), 2);
    }
    
    /*
     * (non-PHPdoc) @see \Zend\EventManager\SharedListenerAggregateInterface::detachShared()
     */
    public function detachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        // TODO Auto-generated method stub
    }
}