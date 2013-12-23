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
namespace User\Manager;

use User\Entity\UserInterface;
use Doctrine\ORM\EntityRepository;
use User\Entity\RoleInterface;

interface UserManagerInterface
{

    /**
     *
     * @param numeric $id            
     * @return UserInterface
     */
    public function getUser($id);

    /**
     *
     * @param string $token            
     * @return UserInterface
     */
    public function findUserByToken($token);

    /**
     *
     * @param string $username            
     * @return UserInterface
     */
    public function findUserByUsername($username);

    /**
     *
     * @param string $email            
     * @return UserInterface
     */
    public function findUserByEmail($email);

    /**
     *
     * @param array $data            
     * @return UserInterface
     */
    public function createUser(array $data);

    /**
     *
     * @return EntityRepository
     */
    public function findAllUsers();

    /**
     *
     * @return EntityRepository
     */
    public function findAllRoles();

    /**
     *
     * @param int $roleId            
     * @return RoleInterface
     */
    public function findRole($roleId);

    /**
     *
     * @param string $role            
     * @return RoleInterface
     */
    public function findRoleByName($name);

    /**
     *
     * @return UserInterface
     */
    public function getUserFromAuthenticator();
}