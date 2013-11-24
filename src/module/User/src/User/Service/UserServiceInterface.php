<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace User\Service;

use Language\Service\LanguageServiceInterface;
use User\Manager\UserManagerInterface;

interface UserServiceInterface
{
    public function getUnassociatedRoles();

    public function addRole($role);
    
    /**
     *
     * @return UserManagerInterface
     */
    public function getManager();

    /**
     *
     * @param UserManagerInterface $manager            
     * @return $this;
     */
    public function setManager(UserManagerInterface $manager);

    public function getRoleNames();

    public function hasRole($roleName);

    public function updateLoginData();

    public function getRoles();

    public function getId();

    public function getEmail();

    public function getUsername();

    public function getPassword();

    public function getLogins();

    public function getLastLogin();

    public function getDate();
    
    public function countRoles();

    public function getRemoved();

    public function setEmail($email);

    public function setUsername($username);

    public function setPassword($password);

    public function setLogins($logins);

    public function setLastLogin($last_login);

    public function setDate($date);
    
    public function setTrashed($trashed);
    
    public function isTrashed();
    
    public function getTrashed();
}