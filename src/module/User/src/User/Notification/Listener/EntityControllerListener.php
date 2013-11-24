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

abstract class EntityControllerListener extends AbstractListener
{
    use \Uuid\Manager\UuidManagerAwareTrait;

    /**
     *
     * @var array
     */
    protected $listeners = array();

    public function onCreate(Event $e)
    {
        $user = $e->getParam('user');
        $entity = $e->getParam('entity');
        $reference = NULL;
        
        foreach($e->getParam('data') as $param){
            if(array_key_exists('to_entity', $param)){
                $reference = $param['to_entity'];
                $reference = $this->getUuidManager()->getUuid($reference);
            }
        }
        $this->logEvent($e->getTarget(), $user, $entity->getEntity()->getUuidEntity(), $reference);
    }
    
    /*
     * (non-PHPdoc) @see \Zend\EventManager\SharedListenerAggregateInterface::attachShared()
     */
    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('Entity\Controller\EntityController', 'create', array(
            $this,
            'onCreate'
        ), - 1);
    }
    
    /*
     * (non-PHPdoc) @see \Zend\EventManager\SharedListenerAggregateInterface::detachShared()
     */
    public function detachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        // TODO Auto-generated method stub
    }
}