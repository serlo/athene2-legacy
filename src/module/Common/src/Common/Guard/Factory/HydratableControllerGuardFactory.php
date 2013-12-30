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
        $roleService = $parentLocator->get('ZfcRbac\Service\RoleService');
        $guard = new HydratableControllerGuard($roleService);
        $guard->setServiceLocator($parentLocator);
        
        $guard->setRules($this->options);
        return $guard;
    }
}