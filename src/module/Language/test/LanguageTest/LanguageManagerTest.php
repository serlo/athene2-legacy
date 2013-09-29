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
namespace LanguageTest;

use Language\Manager\LanguageManager;

class LanguageManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $languageManager, $serviceLocatorMock, $entityManagerMock, $entityRepositoryMock, $classResolverMock, $languageServiceMock;

    public function setUp()
    {
        parent::setUp();
        
        $this->languageManager = new LanguageManager();
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->classResolverMock = $this->getMock('ClassResolver\ClassResolver');
        $this->languageServiceMock = $this->getMock('Language\Service\LanguageService');
        $this->languageMock = $this->getMock('Language\Entity\Language');
        $this->entityManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->entityRepositoryMock = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->classResolverMock->expects($this->any())
            ->method('resolveClassName')
            ->will($this->returnValue('Language\Service\LanguageService'));
        $this->serviceLocatorMock->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->languageServiceMock));
        $this->languageServiceMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        $this->languageMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        
        $this->languageManager->setClassResolver($this->classResolverMock);
        $this->languageManager->setServiceLocator($this->serviceLocatorMock);
        $this->languageManager->setObjectManager($this->entityManagerMock);
        $this->languageManager->setFallBackLanguage(1);
    }

    public function testGetLanguage()
    {
        $this->setUpGet();
        $this->assertSame($this->languageServiceMock, $this->languageManager->getLanguage(1));
    }

    public function testGetFallbackLanguage()
    {
        $this->setUpGet();
        $this->assertSame($this->languageServiceMock, $this->languageManager->getFallbackLanugage());
    }

    public function testGetLanguageFromRequest()
    {
        $this->setUpGet();
        $this->assertSame($this->languageServiceMock, $this->languageManager->getLanguageFromRequest());
    }

    private function setUpGet()
    {
        $this->entityManagerMock->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->languageMock));
    }

    public function testFindLanguageByCode()
    {
        $this->entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->entityRepositoryMock));
        
        $this->entityRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(array(
            'code' => 'de'
        ))
            ->will($this->returnValue($this->languageMock));
        
        $this->assertSame($this->languageServiceMock, $this->languageManager->findLanguageByCode('de'));
    }
}