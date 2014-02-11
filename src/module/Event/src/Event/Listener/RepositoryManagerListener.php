<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Event\Listener;

use Zend\EventManager\Event;

class RepositoryManagerListener extends AbstractListener
{

    /**
     * @var array
     */
    protected $listeners = array();

    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $events->attach(
            $this->getMonitoredClass(),
            'commit',
            array(
                $this,
                'onAddRevision'
            ),
            1
        );

        $events->attach(
            $this->getMonitoredClass(),
            'checkout',
            array(
                $this,
                'onCheckout'
            ),
            -1
        );

        $events->attach(
            $this->getMonitoredClass(),
            'reject',
            array(
                $this,
                'onReject'
            ),
            -1
        );
    }

    protected function getMonitoredClass()
    {
        return 'Versioning\RepositoryManager';
    }

    public function onAddRevision(Event $e)
    {
        $repository = $e->getParam('repository')->getUuidEntity();
        $revision   = $e->getParam('revision');
        $user       = $this->getUserManager()->getUserFromAuthenticator();
        $instance   = $this->getInstanceManager()->getInstanceFromRequest();

        $this->logEvent(
            'entity/revision/add',
            $instance,

            $revision,
            array(
                array(
                    'name'  => 'repository',
                    'value' => $repository
                )
            )
        );
    }

    public function onCheckout(Event $e)
    {
        $revision   = $e->getParam('revision');
        $repository = $e->getParam('repository')->getUuidEntity();
        $user       = $e->getParam('actor');
        $instance   = $this->getInstanceManager()->getInstanceFromRequest();

        $this->logEvent(
            'entity/revision/checkout',
            $instance,
            $revision,
            array(
                array(
                    'name'  => 'repository',
                    'value' => $repository
                )
            )
        );
    }

    public function onReject(Event $e)
    {
        $revision   = $e->getParam('revision');
        $repository = $e->getParam('repository')->getUuidEntity();
        $user       = $e->getParam('actor');
        $instance   = $this->getInstanceManager()->getInstanceFromRequest();
        $reason     = $e->getParam('reason');

        $this->logEvent(
            'entity/revision/reject',
            $instance,
            $revision,
            array(
                array(
                    'name'  => 'repository',
                    'value' => $repository
                ),
                array(
                    'name'  => 'reason',
                    'value' => $reason
                )
            )
        );
    }
}
