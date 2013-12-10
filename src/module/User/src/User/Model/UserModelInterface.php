<?php
namespace User\Model;

use Uuid\Entity\UuidHolder;
use Common\Model\Wrapable;
use DateTime;
use User\Entity\RoleInterface;
use Doctrine\Common\Collections\Collection;

interface UserModelInterface extends UuidHolder, Wrapable
{

    /**
     *
     * @return self
     */
    public function getEntity();

    /**
     *
     * @return string
     */
    public function getEmail();

    /**
     *
     * @return string
     */
    public function getUsername();

    /**
     *
     * @return string
     */
    public function getPassword();

    /**
     *
     * @return string
     */
    public function getLogins();

    /**
     *
     * @return DateTime
     */
    public function getLastLogin();

    /**
     *
     * @return DateTime
     */
    public function getDate();

    /**
     *
     * @param string $email            
     * @return self
     */
    public function setEmail($email);

    /**
     *
     * @param string $username            
     * @return self
     */
    public function setUsername($username);

    /**
     *
     * @param string $password            
     * @return self
     */
    public function setPassword($password);

    /**
     *
     * @param DateTime $lastLogin           
     * @return self          
     */
    public function setLastLogin(DateTime $lastLogin);

    /**
     *
     * @param DateTime $date
     * @return self
     */
    public function setDate(DateTime $date);

    /**
     * 
     * @param RoleInterface $role
     * @return self
     */
    public function addRole(RoleInterface $role);

    /**
     * 
     * @return Collection|RoleInterface[]
     */
    public function getRoles();

    /**
     * 
     * @return string
     */
    public function getToken();

    /**
     *
     * @return self
     */
    //public function generateToken();

    /**
     * 
     * @param RoleInterface $role
     * @return self
     */
    public function removeRole(RoleInterface $role);

    /**
     * 
     * @param unknown $id
     */
    //public function hasRole($id);

    //public function getRoleNames();
}