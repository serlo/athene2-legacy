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
namespace User\Entity;

use ZfcRbac\Identity\IdentityInterface;

interface UserInterface extends IdentityInterface
{

    public function getId();
    
    public function getEmail();

    public function getUsername();

    public function getPassword();

    public function getLogins();

    public function getLastLogin();

    public function getDate();
    
    public function setEmail($email);

    public function setUsername($username);

    public function setPassword($password);

    public function setLogins($logins);

    public function setLastLogin($lastLogin);

    public function setDate($date);

    public function addRole(RoleInterface $role);

    public function getRoles();

    public function populate(array $data = array());

    public function getToken();

    public function generateToken();

    public function removeRole(RoleInterface $role);

    public function hasRole($id);

    public function getRoleNames();
}