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

use Common\Listener\AbstractSharedListenerAggregate;
use Zend\EventManager\Event;

class EntityManagerListener extends AbstractSharedListenerAggregate
{
    use\Taxonomy\Manager\TaxonomyManagerAwareTrait;

    public function onCreate(Event $e)
    {
        /* var $entity \Entity\Service\EntityServiceInterface */
        $entity = $e->getParam('entity');
        $data = $e->getParam('data');
        
        if(array_key_exists('taxonomy', $data)) {
            $options = $data['taxonomy'];
            
            $term = $this->getTaxonomyManager()->getTerm($options['term']);
            $this->getTaxonomyManager()->associateWith($options['term'], $entity);
        }
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $events->attach($this->getMonitoredClass(), 'create', [
            $this,
            'onCreate'
        ], 2);
    }

    protected function getMonitoredClass()
    {
        return 'Entity\Manager\EntityManager';
    }
}