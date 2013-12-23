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
namespace User\Notification\Entity;

use \User\Entity\UserInterface;
use \Uuid\Entity\UuidInterface;

interface SubscriptionInterface
{

    /**
     *
     * @param UserInterface $user            
     * @return self
     */
    public function setSubscriber(UserInterface $user);

    /**
     *
     * @return UserInterface
     */
    public function getSubscriber();

    /**
     *
     * @param UuidInterface $uuid            
     * @return self
     */
    public function setSubscribedObject(UuidInterface $uuid);

    /**
     *
     * @return UuidInterface
     */
    public function getSubscribedObject();
    
    /**
     * 
     * @return bool
     */
    public function getNotifyMailman();

    /**
     *
     * @var bool
     * @return self
     */
    public function setNotifyMailman($notifyMailman);
}