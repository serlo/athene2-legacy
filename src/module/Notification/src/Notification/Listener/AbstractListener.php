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

use Common\Listener\AbstractSharedListenerAggregate;
use Notification\SubscriptionManagerInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Notification\SubscriptionManagerAwareTrait;

abstract class AbstractListener extends AbstractSharedListenerAggregate implements SharedListenerAggregateInterface
{
    use SubscriptionManagerAwareTrait;

    public function __construct(SubscriptionManagerInterface $subscriptionManager)
    {
        if (!class_exists($this->getMonitoredClass())) {
            throw new \RuntimeException(sprintf(
                'The class you are trying to attach to does not exist: %s',
                $this->getMonitoredClass()
            ));
        }
        $this->subscriptionManager = $subscriptionManager;
    }

    public function subscribe($user, $object, $notifyMailman)
    {
        $this->getSubscriptionManager()->subscribe($user, $object, $notifyMailman);
    }
}
