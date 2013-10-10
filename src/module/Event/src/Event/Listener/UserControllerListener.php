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

class UserControllerListener extends AbstractMvcListener
{
    
    /**
     *
     * @var array
     */
    protected $listeners = array();
    
    public function onRegister(Event $e)
    {
        $user = $e->getParam('user');
        $language = $e->getParam('language');
        $this->logEvent($e->getTarget(), $language, $user, $user);
    }
    
	/* (non-PHPdoc)
     * @see \Zend\EventManager\SharedListenerAggregateInterface::attachShared()
     */
    public function attachShared (\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('User\Controller\UserController', 'register', array(
            $this,
            'onRegister'
        ));
    }

	/* (non-PHPdoc)
     * @see \Zend\EventManager\SharedListenerAggregateInterface::detachShared()
     */
    public function detachShared (\Zend\EventManager\SharedEventManagerInterface $events)
    {
        // TODO Auto-generated method stub
        
    }

}