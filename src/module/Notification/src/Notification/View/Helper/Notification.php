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
namespace Notification\View\Helper;

use Doctrine\Common\Collections\Collection;
use Zend\View\Helper\AbstractHelper;

class Notification extends AbstractHelper
{
    use\Notification\NotificationManagerAwareTrait, \User\Manager\UserManagerAwareTrait;

    protected $template, $aggregatedUsers;

    public function __construct()
    {
        $this->template = 'user/notification/notifications';
    }

    public function aggregateUsers(Collection $users)
    {
        $this->aggregatedUsers = array();
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
        $usernames = array();
        foreach ($this->aggregatedUsers as $user) {
            $usernames[] = $user->getUsername();
        }

        return implode(', ', $usernames);
    }

    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    public function render()
    {
        $output = '';
        $user   = $this->getUserManager()->getUserFromAuthenticator();
        if ($user) {
            $output .= $this->getView()->partial(
                $this->template,
                array(
                    'notifications' => $this->getNotificationManager()->findNotificationsBySubsriber($user)
                )
            );
        }

        return $output;
    }
}