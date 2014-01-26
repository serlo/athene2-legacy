<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Migrator\Worker;

use Authorization\Service\RoleServiceInterface;
use Doctrine\ORM\EntityManager;
use Migrator\Converter\ConverterChain;
use User\Manager\UserManagerInterface;
use Zend\Validator\EmailAddress;
use ZfcRbac\Service\AuthorizationService;

class UserWorker implements Worker
{
    /**
     * @var EntityManager
     */
    protected $objectManager;

    /**
     * @var ConverterChain
     */
    protected $converterChain;

    /**
     * @var \User\Manager\UserManagerInterface
     */
    protected $userManager;

    /**
     * @var AuthorizationService
     */
    protected $authorizationService;

    /**
     * @var RoleServiceInterface
     */
    protected $roleService;

    public function __construct(
        EntityManager $objectManager,
        UserManagerInterface $userManagerInterface,
        ConverterChain $converterChain,
        AuthorizationService $authorizationService,
        RoleServiceInterface $roleService
    ) {
        $this->objectManager        = $objectManager;
        $this->userManager          = $userManagerInterface;
        $this->converterChain       = $converterChain;
        $this->authorizationService = $authorizationService;
        $this->roleService          = $roleService;
    }

    public function migrate(array & $results, array &$workload)
    {
        $this->authorizationService->setAssertion('user.create', null);

        $users     = $this->objectManager->getRepository('Migrator\Entity\User')->findAll();
        $validator = new EmailAddress();

        foreach ($users as $user) {
            if ($user->getUsername() == 'arekkas' || $user->getUsername() == 'devuser' || !$validator->isValid(
                    $user->getEmail()
                )
            ) {
                continue;
            }

            $entity = $this->userManager->createUser(
                [
                    'email'    => $user->getEmail(),
                    'username' => $user->getUsername(),
                    'password' => $user->getPassword()
                ]
            );

            $this->roleService->grantIdentityRole(2, $entity->getId());

            $this->userManager->results['user'][$user->getId()] = $entity;
        }

        $this->userManager->flush();

        return $results;
    }

    public function getWorkload(){
        return  [];
    }
}
