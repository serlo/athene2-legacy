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

class LinkControllerListener extends AbstractMvcListener
{

    public function onLink(Event $e)
    {
        $entity = $e->getParam('entity');
        $user = $e->getParam('user');
        $language = $e->getParam('language');
        
        $params = array(
            array(
                'name' => 'parent',
                'object' => $e->getParam('parent')->getUuidEntity()
            )
        );
        
        $this->logEvent('entity/link/create', $language, $user, $entity, $params);
    }

    public function onUnLink(Event $e)
    {
        $entity = $e->getParam('entity');
        $user = $e->getParam('user');
        $language = $e->getParam('language');
        
        $params = array(
            array(
                'name' => 'parent',
                'object' => $e->getParam('parent')->getUuidEntity()
            )
        );
        
        $this->logEvent('entity/link/remove', $language, $user, $entity, $params);
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'unlink', array(
            $this,
            'onUnlink'
        ));
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'link', array(
            $this,
            'onLink'
        ));
    }

    protected function getMonitoredClass()
    {
        return 'Entity\Controller\LinkController';
    }
}