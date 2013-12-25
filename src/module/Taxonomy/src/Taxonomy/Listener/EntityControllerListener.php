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
namespace Taxonomy\Listener;

use Zend\EventManager\Event;
use Common\Listener\AbstractSharedListenerAggregate;

class EntityControllerListener extends AbstractSharedListenerAggregate
{
    use\Taxonomy\Manager\TaxonomyManagerAwareTrait;

    public function onCreate(Event $e)
    {
        /* var $entity \Entity\Service\EntityServiceInterface */
        $entity = $e->getParam('entity');
        $data = $e->getParam('query');
        
        $options = $data['taxonomy'];
        
        $term = $this->getTaxonomyManager()->getTerm($options['term']);
        
        $term->associateObject('entities', $entity);
        
        $e->getTarget()
            ->getEventManager()
            ->trigger('addToTerm', $this, array(
            'entity' => $entity,
            'term' => $term
        ));
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'create', array(
            $this,
            'onCreate'
        ), 2);
    }

    protected function getMonitoredClass()
    {
        return 'Entity\Controller\EntityController';
    }
}