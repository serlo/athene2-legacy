<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Notification\Listener;

use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;
use User\Entity\UserInterface;

class AuthenticationControllerListener extends AbstractListener
{
    public function onActivated(Event $e)
    {
        /* @var $user UserInterface */
        $user = $e->getParam('user');
        $this->subscribe($user, $user, true);
    }

    public function attachShared(SharedEventManagerInterface $events)
    {
        $events->attach(
            $this->getMonitoredClass(),
            'activated',
            [
                $this,
                'onActivated'
            ]
        );
    }

    protected function getMonitoredClass()
    {
        return 'Authentication\Controller\AuthenticationController';
    }
}