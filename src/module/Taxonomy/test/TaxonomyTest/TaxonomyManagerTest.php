<?php
namespace TaxonomyTest;

use Taxonomy\Manager\TaxonomyManager;
use Zend\Stdlib\ArrayUtils;
use Taxonomy\Service\TermService;

class TaxonomyManagerTest extends AbstractTestCase
{

    protected $config, $taxonomyManager, $entityManagerMock, $classResolverMock, $serviceLocatorMock, $termTaxonomyMock, $taxonomyMock, $termServiceMock, $languageManagerMock, $languageServiceMock, $uuidManagerMock, $termManagerMock, $sharedTaxonomyManagerMock;

    public function setUp()
    {
        $this->taxonomyManager = new TaxonomyManager();
        
        $this->entityManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->classResolverMock = $this->getMock('ClassResolver\ClassResolver');
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->termTaxonomyMock = $this->getMock('Taxonomy\Entity\TermTaxonomy');
        $this->taxonomyMock = $this->getMock('Taxonomy\Entity\Taxonomy');
        $this->termServiceMock = new TermService();
        $this->languageManagerMock = $this->getMock('Language\Manager\LanguageManager');
        $this->languageServiceMock = $this->getMock('Language\Service\LanguageService');
        $this->uuidManagerMock = $this->getMock('Uuid\Manager\UuidManager');
        $this->termManagerMock = $this->getMock('Term\Manager\TermManager');
        $this->sharedTaxonomyManagerMock = $this->getMock('Taxonomy\Manager\SharedTaxonomyManager');
        $this->languageServiceMock = $this->getMock('Language\Service\LanguageService');
        
        $this->taxonomyManager->setObjectManager($this->entityManagerMock);
        $this->taxonomyManager->setClassResolver($this->classResolverMock);
        $this->taxonomyManager->setEntity($this->taxonomyMock);
        $this->taxonomyManager->setLanguageService($this->languageServiceMock);
        $this->taxonomyManager->setServiceLocator($this->serviceLocatorMock);
        $this->taxonomyManager->setSharedTaxonomyManager($this->sharedTaxonomyManagerMock);
        $this->taxonomyManager->setTermManager($this->termManagerMock);
        $this->taxonomyManager->setUuidManager($this->uuidManagerMock);
        
        $this->serviceLocatorMock->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->termServiceMock));
        
        $this->termTaxonomyMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));
        
        $this->classResolverMock->expects($this->any())
            ->method('resolveClassName')
            ->will($this->returnValue('Foobar'));
        
        $this->config = array(
            'options' => array(
                'allowed_parents' => array(
                    'foobarSimple'
                ),
                'radix_enabled' => false
            )
        );
        $this->taxonomyManager->setConfig($this->config);
        $this->taxonomyManager->setCheckClassInheritance(false);
    }

    public function testGetTerm()
    {
        $this->entityManagerMock->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->termTaxonomyMock));
        
        $this->termTaxonomyMock->expects($this->once())
            ->method('getTaxonomy')
            ->will($this->returnValue($this->taxonomyMock));
        
        $this->assertEquals($this->termServiceMock, $this->taxonomyManager->getTerm(2));
        $this->assertEquals($this->termServiceMock, $this->taxonomyManager->getTerm('2'));
        $this->assertEquals($this->taxonomyManager, $this->taxonomyManager->getTerm(2)->getManager());
        $this->assertEquals(2, $this->taxonomyManager->getTerm(2)
            ->getId());
    }

    public function testGetTermWithDifferentTaxonomy()
    {
        $this->entityManagerMock->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->termTaxonomyMock));
        
        $taxonomyMockB = $this->getMock('Taxonomy\Entity\Taxonomy');
        
        $this->termTaxonomyMock->expects($this->atLeastOnce())
            ->method('getTaxonomy')
            ->will($this->returnValue($taxonomyMockB));
        
        $taxonomyManagerMock = $this->getMock('Taxonomy\Manager\TaxonomyManager');
        
        $this->sharedTaxonomyManagerMock->expects($this->once())
            ->method('getTaxonomy')
            ->will($this->returnValue($taxonomyManagerMock));
        
        $this->assertEquals($taxonomyManagerMock, $this->taxonomyManager->getTerm(2)->getManager());
        $this->assertEquals(2, $this->taxonomyManager->getTerm(2)
            ->getId());
    }

    /**
     * @expectedException \Taxonomy\Exception\InvalidArgumentException
     */
    public function testGetTermException()
    {
        $this->taxonomyManager->getTerm('fa23');
    }

    /**
     * @expectedException \Taxonomy\Exception\TermNotFoundException
     */
    public function testGetTermNotFoundException()
    {
        $this->taxonomyManager->getTerm(23);
    }
}