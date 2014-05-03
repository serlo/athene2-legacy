<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace User\Manager;

trait UserManagerAwareTrait
{

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @return \User\Manager\UserManagerInterface
     *         $userManager
     */
    public function getUserManager()
    {
        return $this->userManager;
    }

    /**
     * @param \User\Manager\UserManagerInterface $userManager
     * @return self
     */
    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
        return $this;
    }
}
