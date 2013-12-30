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

interface RoleInterface
{
    public function getId();
    
    public function getName();

    public function getDescription();

    public function setName($name);

    public function setDescription($description);

    public function addUser(UserInterface $user);

    public function removeUser(UserInterface $user);

    public function getUsers();
}