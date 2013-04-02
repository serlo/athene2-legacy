<?php
namespace Auth\Service;

use Zend\Authentication\Adapter\DbTable;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\Authentication\Storage\Session;

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
        
        $authService->setUserService($serviceLocator->get('User\Service\UserService'));
        
        $authService->setLanguageService($serviceLocator->get('Core\Service\LanguageService'));
        $authService->setSubjectService($serviceLocator->get('Core\Service\SubjectService'));
        
        $authService->setUser($authService->getUserService()
            ->get($zendAuthService
            ->getIdentity()));
        
        $authService->prepareRoles('\Zend\Permissions\Acl\Role\GenericRole');
        
        return $authService;
    }
}