<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace ClassResolverTest;

use ClassResolver\ClassResolverFactory;
use Zend\ServiceManager\ServiceManager;

class ClassResolverFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testFactory()
    {
        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            'Config',
            [
                'class_resolver' => [
                    'FooInterface' => 'Bar'
                ]
            ]
        );

        $factory       = new ClassResolverFactory();
        $classResolver = $factory->createService($serviceManager);

        $this->assertInstanceOf('ClassResolver\ClassResolver', $classResolver);
        $this->assertSame($serviceManager, $classResolver->getServiceLocator());
    }
}