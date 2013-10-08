<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Notification\Entity\SubscriptionInterface;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="subscription")
 */
class Subscription implements SubscriptionInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Event\Entity\EventLog")
     * @ORM\JoinColumn(name="uuid_id", referencedColumnName="id")
     */
    protected $object;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(type="boolean", name="notify_mailman")
     */
    protected $notifyMailman;
    
	/* (non-PHPdoc)
     * @see \User\Notification\Entity\SubscriptionInterface::setSubscriber()
     */
    public function setSubscriber (\User\Entity\UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

	/* (non-PHPdoc)
     * @see \User\Notification\Entity\SubscriptionInterface::getSubscriber()
     */
    public function getSubscriber ()
    {
        return $this->user;
    }

	/* (non-PHPdoc)
     * @see \User\Notification\Entity\SubscriptionInterface::setSubscribedObject()
     */
    public function setSubscribedObject (\Uuid\Entity\UuidInterface $uuid)
    {
        $this->object = $uuid;
        return $this;
    }

	/* (non-PHPdoc)
     * @see \User\Notification\Entity\SubscriptionInterface::getSubscribedObject()
     */
    public function getSubscribedObject ()
    {
        return $this->object;
    }

}