<?php
/**
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace TermTest;

use Term\Manager\TermManager;

/**
 * @codeCoverageIgnore
 */
class TermManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var TermManager
     */
    protected $termManager;

    public function setUp()
    {
        $this->termManager = new TermManager();
        
        $classResolverMock = $this->getMock('ClassResolver\ClassResolver');
        $entityManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->repositoryMock = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $termServiceMock = $this->getMock('Term\Service\TermService');
        $this->termMock = $this->getMocK('Term\Entity\TermEntity');
        
        $classResolverMock->expects($this->any())
            ->method('resolveClassName')
            ->will($this->returnValue('Term\Entity\Term'));
        $serviceLocatorMock->expects($this->any())
            ->method('get')
            ->will($this->returnValue($termServiceMock));
        $termServiceMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        $this->termMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        $entityManagerMock->expects($this->any())
            ->method('find')
            ->will($this->returnValue($this->termMock));
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->repositoryMock));
        
        $this->termManager->setObjectManager($entityManagerMock);
        $this->termManager->setClassResolver($classResolverMock);
        $this->termManager->setCheckClassInheritance(false);
        $this->termManager->setServiceLocator($serviceLocatorMock);
    }

    public function testGetTerm()
    {
        $this->assertEquals(1, $this->termManager->getTerm(1)
            ->getId());
    }

    /**
     * @expectedException \Term\Exception\InvalidArgumentException
     */
    public function testGetTermInvalidArgumentException(){
        $this->termManager->getTerm('asdf');
    }
    
    
    public function testFindTermByName()
    {
        $this->repositoryMock->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($this->termMock));
        
        $languageServiceMock = $this->getMock('Language\Service\LanguageService');
        
        $this->assertEquals(1, $this->termManager->findTermByName('somename', $languageServiceMock)
            ->getId());
    }

    public function testFindTermBySlug()
    {
        $this->repositoryMock->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($this->termMock));
        $languageServiceMock = $this->getMock('Language\Service\LanguageService');
        
        $this->assertEquals(1, $this->termManager->findTermBySlug('someslug', $languageServiceMock)
            ->getId());
    }

    public function testCreateTerm()
    {
        $this->termManager->getClassResolver()->expects($this->any())
            ->method('resolve')
            ->will($this->returnValue(new \Term\Entity\TermEntity()));
        $languageServiceMock = $this->getMock('Language\Service\LanguageService');
        
        $this->assertNotNull($this->termManager->createTerm('a', 'b', $languageServiceMock));
    }
}