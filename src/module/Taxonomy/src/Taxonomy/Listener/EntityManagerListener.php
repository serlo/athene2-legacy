<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Taxonomy\Listener;

use Common\Listener\AbstractSharedListenerAggregate;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;

class EntityManagerListener extends AbstractSharedListenerAggregate
{
    use TaxonomyManagerAwareTrait;

    public function onCreate(Event $e)
    {
        $entity = $e->getParam('entity');
        $data   = $e->getParam('data');

        if (array_key_exists('taxonomy', $data)) {
            $options = $data['taxonomy'];

            $this->getTaxonomyManager()->associateWith($options['term'], $entity);
        }
    }

    public function attachShared(SharedEventManagerInterface $events)
    {
        $events->attach($this->getMonitoredClass(), 'create', [$this, 'onCreate'], 2);
    }

    protected function getMonitoredClass()
    {
        return 'Entity\Manager\EntityManager';
    }
}
