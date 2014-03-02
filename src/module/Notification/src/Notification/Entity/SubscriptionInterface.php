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
namespace Notification\Entity;

use DateTime;
use User\Entity\UserInterface;
use Uuid\Entity\UuidInterface;

interface SubscriptionInterface
{

    /**
     * @return bool
     */
    public function getNotifyMailman();

    /**
     * @return UuidInterface
     */
    public function getSubscribedObject();

    /**
     * @return UserInterface
     */
    public function getSubscriber();

    /**
     * @return DateTime
     */
    public function getTimestamp();

    /**
     * @var bool
     * @return void
     */
    public function setNotifyMailman($notifyMailman);

    /**
     * @param UuidInterface $uuid
     * @return void
     */
    public function setSubscribedObject(UuidInterface $uuid);

    /**
     * @param UserInterface $user
     * @return void
     */
    public function setSubscriber(UserInterface $user);
}