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
namespace Entity\Plugin\Taxonomy\Listener;

use Zend\EventManager\Event;
use Common\Listener\AbstractSharedListenerAggregate;

class EntityControllerListener extends AbstractSharedListenerAggregate
{
    use \Taxonomy\Manager\SharedTaxonomyManagerAwareTrait;

    public function onCreate(Event $e)
    {
        /* var $entity \Entity\Service\EntityServiceInterface */
        $entity = $e->getParam('entity');
        $data = $e->getParam('query');
        
        foreach ($entity->getScopesForPlugin('taxonomy') as $scope) {
            if (array_key_exists($scope, $data)) {
                
                $options = $data[$scope];
                $term = $this->getSharedTaxonomyManager()->getTerm($options['term']);
                
                $entity->plugin($scope)->addToTerm($term->getId());
                
                $e->getTarget()
                    ->getEventManager()
                    ->trigger('addToTerm', $this, array(
                    'entity' => $entity,
                    'term' => $term
                ));
            }
        }
    }
    
    /*
     * (non-PHPdoc) @see \Zend\EventManager\SharedListenerAggregateInterface::attachShared()
     */
    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'create', array(
            $this,
            'onCreate'
        ), 2);
    }
    
    /*
     * (non-PHPdoc) @see \Common\Listener\AbstractSharedListenerAggregate::getMonitoredClass()
     */
    protected function getMonitoredClass()
    {
        return 'Entity\Controller\EntityController';
    }
}