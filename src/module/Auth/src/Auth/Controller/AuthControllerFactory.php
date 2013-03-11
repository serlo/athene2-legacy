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
        
        return $ctr;
    }
}