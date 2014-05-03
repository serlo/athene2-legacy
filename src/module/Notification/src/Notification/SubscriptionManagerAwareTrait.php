<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Notification;

trait SubscriptionManagerAwareTrait
{

    /**
     * @var SubscriptionManagerInterface
     */
    protected $subscriptionManager;

    /**
     * @return SubscriptionManagerInterface $subscriptionManager
     */
    public function getSubscriptionManager()
    {
        return $this->subscriptionManager;
    }

    /**
     * @param SubscriptionManagerInterface $subscriptionManager
     * @return self
     */
    public function setSubscriptionManager(SubscriptionManagerInterface $subscriptionManager)
    {
        $this->subscriptionManager = $subscriptionManager;

        return $this;
    }
}
