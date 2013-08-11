<?php
namespace Auth\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthControllerFactory implements FactoryInterface
{

    public function createService (ServiceLocatorInterface $serviceLocator)
    {
        $ctr = new AuthController();
        $ctr->setLoginForm(new \Auth\Form\Login());
        $ctr->setObjectManager($serviceLocator->getServiceLocator()->get('EntityManager'));
        $ctr->setUserManager($serviceLocator->getServiceLocator()->get('User\Manager\UserManager'));
        $ctr->setHashService($serviceLocator->getServiceLocator()->get('Auth\Service\HashService'));
        return $ctr;
    }
}