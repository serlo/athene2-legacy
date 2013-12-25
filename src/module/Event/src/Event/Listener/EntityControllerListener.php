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

class EntityControllerListener extends AbstractMvcListener
{
    
    public function onCreate(Event $e)
    {
        $entity = $e->getParam('entity');
        $user = $e->getParam('user');
        $language = $e->getParam('language');
        $this->logEvent('entity/create', $language, $user, $entity);
    }
    
    public function onLink(Event $e)
    {
        $entity = $e->getParam('entity');
        $user = $e->getParam('user');
        $language = $e->getParam('language');
        
        $params = array(array(
            'name' => 'parent',
            'object' => $e->getParam('parent')->getUuidEntity()
        ));
        
        $this->logEvent('entity/link/create', $language, $user, $entity, $params);
    }
    
    
    public function attachShared (\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'create', array(
            $this,
            'onCreate'
        ));
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'link', array(
            $this,
            'onLink'
        ));
    }
    
    protected function getMonitoredClass ()
    {
        return 'Entity\Controller\EntityController';
    }
}