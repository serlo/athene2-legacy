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
namespace LearningResource\Plugin\Link\Listener;

use Entity\Plugin\Listener\AbstractListener;
use Zend\EventManager\Event;
use LearningResource\Plugin\Link\LinkPlugin;

class Link extends AbstractListener
{
    /*
     * (non-PHPdoc) @see \Zend\EventManager\ListenerAggregateInterface::attach()
     */
    public function attach(\Zend\EventManager\EventManagerInterface $events)
    {
        $plugin = "a";
        $entityManager = "b";
        $this->listeners[] = $events->attach('createEntity.postFlush', function (Event $e) use($plugin, $entityManager)
        {
            /* var $entity \Entity\Service\EntityServiceInterface */
            $entity = $e->getParam('entity');
            $data = $e->getParam('data');
            
            foreach ($entity->getScopesForPlugin('link') as $scope) {
                
                if (array_key_exists($scope, $data)) {
                    $options = $data[$scope];
                    
                    $toEntity = $entityManager->getEntity($options['to_entity']);
                    $addAs = $options['as'];
                    
                    if ($addAs == 'parent') {
                        $entity->$scope()
                            ->addChild($toEntity);
                    } else {
                        $entity->$scope()
                            ->addParent($toEntity);
                    }
                }
            }
        }, 2);
    }
}