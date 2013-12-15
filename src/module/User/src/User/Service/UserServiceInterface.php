<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace User\Service;

use User\Model\UserModelInterface;
use User\Manager\UserManagerAwareInterface;
use User\Entity\UserInterface;

interface UserServiceInterface extends UserModelInterface, UserManagerAwareInterface
{
    public function updateLoginData();

    public function getUnassociatedRoles();
    
    public function setEntity(UserInterface $user);
    
    /**
     * 
     * @return UserInterface
     */
    public function getEntity();
}