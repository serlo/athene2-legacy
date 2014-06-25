<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Notification\View\Helper;

use Doctrine\Common\Collections\Collection;
use Notification\Entity\NotificationInterface;
use Notification\NotificationManagerInterface;
use User\Manager\UserManagerInterface;
use Zend\Cache\Storage\StorageInterface;
use Zend\View\Helper\AbstractHelper;

class Notification extends AbstractHelper
{
    protected $template, $aggregatedUsers;

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var NotificationManagerInterface
     */
    protected $notificationManager;

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @param NotificationManagerInterface $notificationManager
     * @param StorageInterface             $storage
     * @param UserManagerInterface         $userManager
     */
    public function __construct(
        NotificationManagerInterface $notificationManager,
        StorageInterface $storage,
        UserManagerInterface $userManager
    ) {
        $this->storage             = $storage;
        $this->notificationManager = $notificationManager;
        $this->userManager         = $userManager;
        $this->template = 'user/notification/notifications';
    }

    public function aggregateUsers(Collection $users)
    {
        $this->aggregatedUsers = [];
        foreach ($users as $actor) {
            if (!$actor instanceof \User\Entity\UserInterface) {
                throw new \User\Exception\RuntimeException(sprintf(
                    'Expected UserInterface but got %s',
                    gettype($actor)
                ));
            }

            if (!in_array($actor, $this->aggregatedUsers)) {
                $this->aggregatedUsers[] = $actor;
            }
        }

        return $this->aggregatedUsers;
    }

    public function getAggregatedUsernames()
    {
        $usernames = [];
        foreach ($this->aggregatedUsers as $user) {
            $usernames[] = $user->getUsername();
        }

        return implode(', ', $usernames);
    }

    public function getSeen(Collection $collection)
    {
        return $collection->filter(
            function (NotificationInterface $notification) {
                return !$notification->getSeen();
            }
        );
    }

    public function render()
    {
        $user = $this->userManager->getUserFromAuthenticator();
        $key  = hash('sha256', serialize($user));

        if ($this->storage->hasItem($key)) {
            return $this->storage->getItem($key);
        }

        if ($user) {
            $output = $this->getView()->partial(
                $this->template,
                [
                    'notifications' => $this->notificationManager->findNotificationsBySubscriber($user, 20)
                ]
            );
            $this->storage->setItem($key, $output);
            return $output;
        }

        return '';
    }

    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }
}
