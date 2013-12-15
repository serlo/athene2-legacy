<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace User\Service;

use User\Entity\User;
use Common\Normalize\Normalized;
use User\Entity\RoleInterface;
use DateTime;
use Uuid\Entity\UuidInterface;
use User\Entity\UserInterface;

class UserService implements UserServiceInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\User\Manager\UserManagerAwareTrait;

    /**
     *
     * @var UserInterface
     */
    protected $entity;

    public function getEntity()
    {
        return $this->entity;
    }

    public function persist()
    {
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function flush()
    {
        $this->getObjectManager()->flush($this->getEntity());
        return $this;
    }

    public function getUnassociatedRoles()
    {
        $roles = $this->getUserManager()->findAllRoles();
        $return = array();
        foreach ($roles as $role) {
            if (! $this->hasRole($role)) {
                $return[] = $role;
            }
        }
        return $return;
    }

    public function updateLoginData()
    {
        $this->getEntity()->setLogins($this->getEntity()
            ->getLogins() + 1);
        $this->getEntity()->setLastLogin(new DateTime("now"));
        return $this;
    }

    public function generateToken()
    {
        $this->getEntity()->generateToken();
        return $this;
    }

    public function getUuid()
    {
        return $this->getEntity()->getUuid();
    }

    public function getHolderName()
    {
        return $this->getEntity()->getHolderName();
    }

    public function getUuidEntity()
    {
        return $this->getEntity()->$uuid();
    }

    public function getTrashed()
    {
        return $this->getEntity()->getTrashed();
    }

    public function getRoles()
    {
        return $this->getEntity()->getRoles();
    }

    public function countRoles()
    {
        return count($this->getRoles());
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function getEmail()
    {
        return $this->getEntity()->getEmail();
    }

    public function getUsername()
    {
        return $this->getEntity()->getUsername();
    }

    public function getPassword()
    {
        return $this->getEntity()->getPassword();
    }

    public function getLogins()
    {
        return $this->getEntity()->getLogins();
    }

    public function getLastLogin()
    {
        return $this->getEntity()->getLastLogin();
    }

    public function getDate()
    {
        return $this->getEntity()->getDate();
    }

    public function getToken()
    {
        return $this->getEntity()->getToken();
    }

    public function getAdsEnabled()
    {
        return $this->getEntity()->getAdsEnabled();
    }

    public function getRemoved()
    {
        return $this->getEntity()->getRemoved();
    }

    public function setEmail($email)
    {
        $this->getEntity()->setEmail($email);
        return $this;
    }

    public function setUsername($username)
    {
        $this->getEntity()->setUsername($username);
        return $this;
    }

    public function setPassword($password)
    {
        $this->getEntity()->setPassword($password);
        return $this;
    }

    public function setLogins($logins)
    {
        $this->getEntity()->setLogins($logins);
        return $this;
    }

    public function setLastLogin(DateTime $last_login)
    {
        $this->getEntity()->setLastLogin($last_login);
        return $this;
    }

    public function setDate(DateTime $date)
    {
        $this->getEntity()->setDate($date);
        return $this;
    }

    public function setAdsEnabled($ads_enabled)
    {
        $this->getEntity()->setAdsEnabled($ads_enabled);
        return $this;
    }

    public function setTrashed($trashed)
    {
        return $this->getEntity()->setTrashed($trashed);
    }

    public function addRole(RoleInterface $role)
    {
        $this->getEntity()->addRole($role);
        return $this;
    }

    public function removeRole(RoleInterface $role)
    {
        $this->getEntity()->removeRole($role);
        return $this;
    }

    public function hasRole(RoleInterface $role)
    {
        return $this->getEntity()->hasRole($role);
    }

    public function setUuid(UuidInterface $uuid)
    {
        return $this->getEntity()->setUuid($uuid);
    }

    public function setEntity(UserInterface $user)
    {
        $this->entity = $user;
    }
}