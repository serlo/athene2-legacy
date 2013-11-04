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
use User\Manager\UserManagerInterface;

class UserService implements UserServiceInterface
{
    use\Common\Traits\EntityAwareTrait,\Common\Traits\ObjectManagerAwareTrait;

    /**
     *
     * @var UserManagerInterface
     */
    protected $manager;

    /**
     *
     * @return UserManagerInterface $manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     *
     * @param UserManagerInterface $manager            
     * @return $this
     */
    public function setManager(UserManagerInterface $manager)
    {
        $this->manager = $manager;
        return $this;
    }

    public function getRoleNames()
    {
        return $this->getEntity()->getRoleNames();
    }

    public function hasRole($id)
    {
        return $this->getEntity()->hasRole($id);
    }

    public function getUnassociatedRoles()
    {
        $roles = $this->getManager()->findAllRoles();
        $return = array();
        foreach ($roles as $role) {
            if (! $this->hasRole($role->getId())) {
                $return[] = $role;
            }
        }
        return $return;
    }

    public function updateLoginData()
    {
        $this->getEntity()->setLogins($this->getEntity()
            ->getLogins() + 1);
        $this->getEntity()->setLastLogin(new \DateTime("now"));
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
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

    public function addRole($id)
    {
        $role = $this->getManager()->findRole($id);
        $this->getEntity()->addRole($role);
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function removeRole($id)
    {
        $role = $this->getManager()->findRole($id);
        $this->getEntity()->removeRole($role);
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function generateToken()
    {
        $this->getEntity()->generateToken();
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
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

    public function getGivenname()
    {
        return $this->getEntity()->getGivenname();
    }

    public function getLastname()
    {
        return $this->getEntity()->getLastname();
    }

    public function getToken()
    {
        return $this->getEntity()->getToken();
    }

    public function getGender()
    {
        return $this->getEntity()->getGender();
    }

    public function getAdsEnabled()
    {
        return $this->getEntity()->getAdsEnabled();
    }

    public function getRemoved()
    {
        return $this->getEntity()->getRemoved();
    }

    public function setUserRoles($userRoles)
    {
        $this->getEntity()->setUserRoles($userRoles);
        return $this;
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

    public function setLastLogin($last_login)
    {
        $this->getEntity()->setLastLogin($last_login);
        return $this;
    }

    public function setDate($date)
    {
        $this->getEntity()->setDate($date);
        return $this;
    }

    public function setGivenname($givenname)
    {
        $this->getEntity()->setGivenname($givenname);
        return $this;
    }

    public function setLastname($lastname)
    {
        $this->getEntity()->setLastname($lastname);
        return $this;
    }

    public function setGender($gender)
    {
        $this->getEntity()->setGender($gender);
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

    public function isTrashed()
    {
        return $this->getEntity()->isTrashed();
    }

    public function getTrashed()
    {
        return $this->getEntity()->getTrashed();
    }
}