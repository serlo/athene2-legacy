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
namespace Entity\Plugin\Link\Listener;

use Zend\EventManager\Event;
use Common\Listener\AbstractSharedListenerAggregate;

class EntityControllerListener extends AbstractSharedListenerAggregate
{
    
    /*
     * (non-PHPdoc) @see \Zend\EventManager\SharedListenerAggregateInterface::attachShared()
     */
    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'create', function (Event $e)
        {
            /* var $entity \Entity\Service\EntityServiceInterface */
            $entity = $e->getParam('entity');
            $data = $e->getParam('query');
            $user = $e->getParam('user');
            $language = $e->getParam('language');
            
            /* var $entity \Entity\Manager\EntityManagerInterface */
            $entityManager = $entity->getEntityManager();
            if (count($entity->getScopesForPlugin('link'))) {
                $found = false;
                foreach ($entity->getScopesForPlugin('link') as $scope) {
                    if (array_key_exists($scope, $data)) {
                        $options = $data[$scope];
                        
                        $toEntity = $entityManager->getEntity($options['to_entity']);
                        $found = true;

                        $entity->$scope()
                            ->add($toEntity);

                        $e->getTarget()
                            ->getEventManager()
                            ->trigger('link', $this, array(
                            'entity' => $entity,
                            'parent' => $toEntity,
                            'user' => $user,
                            'language' => $language
                        ));
                    }
                }
            }
        }, 2);
    }
    
	/* (non-PHPdoc)
     * @see \Common\Listener\AbstractSharedListenerAggregate::getMonitoredClass()
     */
    protected function getMonitoredClass ()
    {
        return 'Entity\Controller\EntityController';
    }
}