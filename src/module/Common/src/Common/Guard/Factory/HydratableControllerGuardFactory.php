<?php 
namespace Common\Guard\Factory;

use Common\Guard\HydratableControllerGuard;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HydratableControllerGuardFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    /**
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

    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {   
        $parentLocator = $serviceLocator->getServiceLocator();
        
        $authorizationService = $parentLocator->get('ZfcRbac\Service\AuthorizationService');
        $guard = new HydratableControllerGuard( $authorizationService,$this->options);
        if ($parentLocator->get('Page\Manager\PageManager')==null) die(haee);
        $guard->setPageManager($parentLocator->get('Page\Manager\PageManager'));
        $guard->setServiceLocator($serviceLocator);
        return $guard;
    }
}