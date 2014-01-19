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
namespace Notification\Listener;

use Zend\EventManager\Event;

class RepositoryManagerListener extends AbstractListener
{
    use \User\Manager\UserManagerAwareTrait;

    /**
     * @var array
     */
    protected $listeners = array();

    public function onCommitRevision(Event $e)
    {
        $repository = $e->getParam('repository');
        $data       = $e->getParam('data');
        $user       = $this->getUserManager()->getUserFromAuthenticator();

        foreach ($data as $params) {
            if (is_array($params) && array_key_exists('subscription', $params)) {
                $param = $params['subscription'];
                if ($param['subscribe'] === '1') {
                    $notifyMailman = $param['mailman'] === '1' ? true : false;
                    $this->subscribe($user, $repository->getUuidEntity(), $notifyMailman);
                }
            }
        }
    }

    /*
     * (non-PHPdoc) @see \Zend\EventManager\SharedListenerAggregateInterface::attachShared()
     */
    public function attachShared(\Zend\EventManager\SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            $this->getMonitoredClass(),
            'commit',
            array(
                $this,
                'onCommitRevision'
            ),
            2
        );
    }

    protected function getMonitoredClass()
    {
        return 'Versioning\RepositoryManager';
    }
}