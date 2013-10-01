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
namespace LearningResource\Plugin\Taxonomy\Listener;

use Zend\EventManager\Event;
use Entity\Plugin\Listener\AbstractListener;

class Taxonomy extends AbstractListener
{

    public function attach(\Zend\EventManager\EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('createEntity.preFlush', function (Event $e)
        {
            /* var $entity \Entity\Service\EntityServiceInterface */
            $entity = $e->getParam('entity');
            $data = $e->getParam('data');
            
            foreach ($entity->getScopesForPlugin('taxonomy') as $scope) {
                
                if (array_key_exists($scope, $data)) {
                    $options = $data[$scope];
                    $entity->$scope()->addToTerm($options['term']);
                }
            }
        }, 2);
    }
    
}