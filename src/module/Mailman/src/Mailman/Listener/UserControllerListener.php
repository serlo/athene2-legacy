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
namespace Mailman\Listener;

use \Zend\EventManager\Event;
use Zend\View\Model\ViewModel;

class UserControllerListener extends AbstractListener
{
    use\Zend\I18n\Translator\TranslatorAwareTrait;

    public function onRegister(Event $e)
    {
        /* @var $user \User\Service\UserServiceInterface */
        $user = $e->getParam('user');
        
        $subject = new ViewModel();
        $body = new ViewModel(array(
            'user' => $user
        ));
        
        $subject->setTemplate('mailman/messages/register/subject');
        $body->setTemplate('mailman/messages/register/body');
        
        $this->getMailman()->send($user->getEmail(), $this->getMailman()
            ->getDefaultSender(), $this->getRenderer()
            ->render($subject), $this->getRenderer()
            ->render($body));
    }

    public function onRestore(Event $e)
    {
        /* @var $user \User\Service\UserServiceInterface */
        $user = $e->getParam('user');
        
        $subject = new ViewModel();
        $body = new ViewModel(array(
            'user' => $user
        ));
        
        $subject->setTemplate('mailman/messages/restore-password/subject');
        $body->setTemplate('mailman/messages/restore-password/body');
        
        $this->getMailman()->send($user->getEmail(), $this->getMailman()
            ->getDefaultSender(), $this->getRenderer()
            ->render($subject), $this->getRenderer()
            ->render($body));
    }
    
    /*
     * (non-PHPdoc) @see \Zend\EventManager\SharedListenerAggregateInterface::attachShared()
     */
    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'register', array(
            $this,
            'onRegister'
        ), - 1);
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'restore-password', array(
            $this,
            'onRestore'
        ), - 1);
    }
    
	/* (non-PHPdoc)
     * @see \Common\Listener\AbstractSharedListenerAggregate::getMonitoredClass()
     */
    protected function getMonitoredClass ()
    {
        return 'User\Controller\UserController';
    }

}