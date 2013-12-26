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
 */
class DiscussionManagerListener extends AbstractMvcListener
{

    /**
     * Gets executed on 'start'
     *
     * @param Event $e            
     * @return null
     */
    public function onStart(Event $e)
    {
        $language = $e->getParam('language');
        $user = $e->getParam('user');
        $discussion = $e->getParam('discussion');
        
        $params = array(
            array(
                'name' => 'on',
                'object' => $e->getParam('on')
            )
        );
        
        $this->logEvent('discussion/create', $language, $user, $discussion, $params);
    }

    /**
     * Gets executed on 'comment'
     *
     * @param Event $e            
     * @return null
     */
    public function onComment(Event $e)
    {
        $user = $e->getParam('user');
        $language = $e->getParam('language');
        $discussion = $e->getParam('discussion')->getUuidEntity();
        
        $params = array(
            array(
                'name' => 'discussion',
                'object' => $discussion
            )
        );
        
        $comment = $e->getParam('comment');
        $this->logEvent('discussion/comment/create', $language, $user, $comment, $params);
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        // Listens 'start'
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'start', array(
            $this,
            'onStart'
        ));
        
        // Listens on 'comment'
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'comment', array(
            $this,
            'onComment'
        ));
    }

    protected function getMonitoredClass()
    {
        return 'Discussion\DiscussionManager';
    }
}