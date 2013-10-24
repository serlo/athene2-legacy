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
namespace Event\Listener;

use Zend\EventManager\Event;

/**
 * Event Listener for Discussion\Controller\DiscussionController
 * 
 */
class DiscussionControllerListener extends AbstractMvcListener
{
    
    /**
     * An array containing all registered listeners.
     *
     * @var array
     */
    protected $listeners = array();
    
    /**
     * Gets executed on 'start'
     * 
     * @param Event $e
     * @return null
     */
    public function onStart(Event $e)
    {
        $language = $e->getParam('language')->getEntity();
        $user = $e->getParam('user')->getEntity();
        $discussion = $e->getParam('discussion')->getEntity();
        $on = $e->getParam('on');
        $this->logEvent($e->getTarget(), $language, $user, $discussion, $on);
    }
    
    /**
     * Gets executed on 'comment'
     * 
     * @param Event $e
     * @return null
     */
    public function onComment(Event $e)
    {
        $user = $e->getParam('user')->getEntity();
        $language = $e->getParam('language')->getEntity();
        $discussion = $e->getParam('discussion')->getEntity()->getUuidEntity();
        $comment = $e->getParam('comment')->getEntity();
        $this->logEvent($e->getTarget(), $language, $user, $comment, $discussion);
    }
    
    
    public function attachShared (\Zend\EventManager\SharedEventManagerInterface $events)
    {
        // Listens 'start'
        $this->listeners[] = $events->attach('Discussion\Controller\DiscussionController', 'start', array(
            $this,
            'onStart'
        ));
        
        // Listens on 'comment'
        $this->listeners[] = $events->attach('Discussion\Controller\DiscussionController', 'comment', array(
            $this,
            'onComment'
        ));
    }
}