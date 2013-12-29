<?php
namespace Common\Guard\Factory;

use Common\Guard\HydratableControllerGuard;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HydratableControllerGuardFactory implements FactoryInterface, MutableCreationOptionsInterface
{

    /**
     *
     * @var array
     */
    protected $options;

    /**
     * {@inheritDoc}
     */
    public function setCreationOptions(array $options)
    {
        $this->options = $options;
    }

    /**t
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();
        $authorizationService = $parentLocator->get('ZfcRbac\Service\AuthorizationService');
        $guard = new HydratableControllerGuard($authorizationService);
        $guard->setServiceLocator($parentLocator);
        
        $guard->setRules($this->options);
        return $guard;
    }
}