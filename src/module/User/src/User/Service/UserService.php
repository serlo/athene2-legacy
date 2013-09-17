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
use Language\Service\LanguageServiceInterface;

class UserService implements UserServiceInterface
{
    use\Common\Traits\EntityAwareTrait,\Common\Traits\ObjectManagerAwareTrait;

    public function getRoleNames(LanguageServiceInterface $language = NULL){
        if($language) $language = $language->getEntity();
        
        $return = array();
        foreach($this->getEntity()->getRoles($language) as $role){
            $return[] = $role->getName();
        }
        return $return;
    }
    
    
    public function getRoles (LanguageServiceInterface $language = NULL)
    {
        return $this->getEntity()->getRoles($language);
    }
    
    public function updateLoginData(){
        $this->getEntity()->setLogins($this->getEntity()->getLogins()+1);
        $this->getEntity()->setLastLogin(new \DateTime("now"));
        $this->getObjectManager()->persist($this->getEntity());
        return $this;
    }

    public function getId ()
    {
        return $this->getEntity()->getId();
    }

    public function hasRole ($roleName, LanguageServiceInterface $language = NULL)
    {
        $roles = $this->getRoles($language);
        foreach($roles as $roleEntity){
            if($roleEntity->getName() == $roleName){
                return true;
            }
        }
        return false;
    }

    public function getUserRoles ()
    {
        return $this->getEntity()->getUserRoles();
    }

    public function getLogs ()
    {
        return $this->getEntity()->getLogs();
    }

    public function getEmail ()
    {
        return $this->getEntity()->getEmail();
    }

    public function getUsername ()
    {
        return $this->getEntity()->getUsername();
    }

    public function getName ()
    {
        return $this->getEntity()->getName();
    }

    public function getPassword ()
    {
        return $this->getEntity()->getPassword();
    }

    public function getLogins ()
    {
        return $this->getEntity()->getLogins();
    }

    public function getLastLogin ()
    {
        return $this->getEntity()->getLastLogin();
    }

    public function getDate ()
    {
        return $this->getEntity()->getDate();
    }

    public function getGivenname ()
    {
        return $this->getEntity()->getGivenname();
    }

    public function getLastname ()
    {
        return $this->getEntity()->getLastname();
    }

    public function getGender ()
    {
        return $this->getEntity()->getGender();
    }

    public function getAds_enabled ()
    {
        return $this->getEntity()->getAds_enabled();
    }

    public function getRemoved ()
    {
        return $this->getEntity()->getRemoved();
    }

    public function setLogs ($logs)
    {
        $this->getEntity()->setLogs($logs);
        return $this;
    }

    public function setUserRoles ($userRoles)
    {
        $this->getEntity()->setUserRoles($userRoles);
        return $this;
    }

    public function setEmail ($email)
    {
        $this->getEntity()->setEmail($email);
        return $this;
    }

    public function setUsername ($username)
    {
        $this->getEntity()->setUsername($username);
        return $this;
    }

    public function setPassword ($password)
    {
        $this->getEntity()->setPassword($password);
        return $this;
    }

    public function setLogins ($logins)
    {
        $this->getEntity()->setLogins($logins);
        return $this;
    }

    public function setLast_login ($last_login)
    {
        $this->getEntity()->setLast_login($last_login);
        return $this;
    }

    public function setDate ($date)
    {
        $this->getEntity()->setDate($date);
        return $this;
    }

    public function setGivenname ($givenname)
    {
        $this->getEntity()->setGivenname($givenname);
        return $this;
    }

    public function setLastname ($lastname)
    {
        $this->getEntity()->setLastname($lastname);
        return $this;
    }

    public function setGender ($gender)
    {
        $this->getEntity()->setGender($gender);
        return $this;
    }

    public function setAds_enabled ($ads_enabled)
    {
        $this->getEntity()->setAds_enabled($ads_enabled);
        return $this;
    }

    public function setRemoved ($removed)
    {
        $this->getEntity()->setRemoved($removed);
        return $this;
    }
}