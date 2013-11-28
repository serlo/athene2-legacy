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

class RepositoryPluginControllerListener extends AbstractMvcListener
{

    /**
     *
     * @var array
     */
    protected $listeners = array();

    public function onAddRevision(Event $e)
    {
        $entity = $e->getParam('entity')
            ->getEntity()
            ->getUuidEntity();
        $user = $e->getParam('user')->getEntity();
        $language = $e->getParam('language')->getEntity();
        $revision = $e->getParam('revision');
        
        $this->logEvent('entity/revision/add', $language, $user, $revision, array(
            array(
                'name' => 'repository',
                'object' => $entity
            )
        ));
    }

    public function onCheckout(Event $e)
    {
        $user = $e->getParam('user')->getEntity();
        $revision = $e->getParam('revision');
        $entity = $e->getParam('entity')
            ->getEntity()
            ->getUuidEntity();
        $language = $e->getParam('language')->getEntity();
        
        $this->logEvent('entity/revision/checkout', $language, $user, $revision, array(
            array(
                'name' => 'repository',
                'object' => $entity
            )
        ));
    }

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'add-revision', array(
            $this,
            'onAddRevision'
        ), 1);
        
        $this->listeners[] = $events->attach($this->getMonitoredClass(), 'checkout', array(
            $this,
            'onCheckout'
        ), - 1);
    }

    protected function getMonitoredClass()
    {
        return 'Entity\Plugin\Repository\Controller\RepositoryController';
    }
}