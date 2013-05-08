<?php
namespace Navigation\Provider;

use Zend\ServiceManager\ServiceLocatorInterface;

interface ProviderInterface
{
    public function __construct(array $options, ServiceLocatorInterface $serviceLocator);
    public function provideArray();
}