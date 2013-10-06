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
namespace User\Listener\Event;

use Event\Listener\AbstractMvcForwardingListener;
use Zend\EventManager\Event;

class UserForwardingListener extends AbstractMvcForwardingListener
{

    /**
     *
     * @var array
     */
    protected $listeners = array();

    public function attach(\Zend\EventManager\EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('register', array(
            $this,
            'onRegister'
        ));
    }

    public function detach(\Zend\EventManager\EventManagerInterface $events)
    {}

    public function onRegister(Event $e)
    {
        $user = $e->getParam('user');
        $language = $e->getParam('language');
        $object = sprintf('E-Mail `%s`', $user->getEmail());
        $verb = 'registered';
        
        $this->logEvent($e->getTarget(), $language, $user, $user, $object, $verb);
    }
}