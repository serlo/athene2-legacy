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
namespace Auth\Service;

use Zend\Authentication\Adapter\DbTable;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\Authentication\Storage\Session;
use User\Exception\UserNotFoundException;

class AuthServiceFactory implements FactoryInterface
{

    const TABLE_NAME = "user";

    const IDENTITY_COLUMN = "email";

    const CREDENTIAL_COLUMN = "password";

    public function createService (ServiceLocatorInterface $serviceLocator)
    {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        
        $zendAuthService = new \Zend\Authentication\AuthenticationService(new Session(), 

        new DbTable($dbAdapter, self::TABLE_NAME, self::IDENTITY_COLUMN, self::CREDENTIAL_COLUMN));
        
        $hashService = $serviceLocator->get('Auth\Service\HashService');
        $authService = new AuthService();
        
        $authService->setAuthService($zendAuthService);
        $authService->setHashService($hashService);
        $authService->setAdapter($dbAdapter);
        
        $authService->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));
        
        $authService->setAclService($serviceLocator->get('Zend\Permissions\Acl\Acl'));
        
        $authService->setUserManager($serviceLocator->get('User\Manager\UserManager'));
        
        $authService->setLanguageManager($serviceLocator->get('Language\Manager\LanguageManager'));
        //$authService->setSubjectService($serviceLocator->get('Core\Service\SubjectService'));
        
        try {
            try {
                $user = $authService->getUserManager()->get($zendAuthService->getIdentity());
            } catch (\InvalidArgumentException $e) {
                throw new UserNotFoundException();
            }
        } catch (UserNotFoundException $e) {
            $user = ($authService->getUserManager()->createUser());
        }
        $authService->setUser($user);
        
        $authService->prepareRoles('\Zend\Permissions\Acl\Role\GenericRole');
        
        return $authService;
    }
}