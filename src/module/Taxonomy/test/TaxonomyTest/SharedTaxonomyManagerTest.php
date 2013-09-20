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
namespace TaxonomyTest;

use Taxonomy\Manager\SharedTaxonomyManager;
use Doctrine\DBAL\LockMode;

class SharedTaxonomyManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var SharedTaxonomyManager
     */
    protected $sharedTaxonomyManager;

    /**
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $entityManagerMock, $serviceLocatorMock, $repositoryMock, $termTaxonomyMock, $taxonomyManagerMock, $termServiceMock, $languageServiceMock, $languageManagerMock, $classResolverMock, $taxonomyMock;

    public function setUp()
    {
        $config = array(
            'associations' => array(
                'footest' => function  ($sm, $collection)
                {
                    return $collection;
                }
            ),
            'types' => array(
                'foobarSimple' => array(
                    'options' => array(
                        'allowed_associations' => array(),
                        'radix_enabled' => true
                    )
                ),
                'foobar' => array(
                    'options' => array(
                        'allowed_parents' => array(
                            'foobarSimple'
                        ),
                        'radix_enabled' => false
                    )
                )
            )
        );
        
        $stm = $this->sharedTaxonomyManager = new SharedTaxonomyManager($config);
        $stm->setCheckClassInheritance(false);
        
        $this->entityManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->classResolverMock = $this->getMock('ClassResolver\ClassResolver');
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->termTaxonomyMock = $this->getMock('Taxonomy\Entity\TermTaxonomy');
        $this->taxonomyMock = $this->getMock('Taxonomy\Entity\Taxonomy');
        $this->taxonomyManagerMock = $this->getMock('Taxonomy\Manager\TaxonomyManager');
        $this->termServiceMock = $this->getMock('Taxonomy\Service\TermServiceMock');
        $this->languageManagerMock = $this->getMock('Language\Manager\LanguageManager');
        $this->languageServiceMock = $this->getMock('Language\Service\LanguageService');
        
        $this->sharedTaxonomyManager->setObjectManager($this->entityManagerMock);
        $this->sharedTaxonomyManager->setClassResolver($this->classResolverMock);
        $this->sharedTaxonomyManager->setLanguageManager($this->languageManagerMock);
        $this->sharedTaxonomyManager->setServiceLocator($this->serviceLocatorMock);
        
        /*
         * $this->taxonomyManagerMock->expects($this->any()) ->method('getId') ->will($this->returnValue(10)); $this->termServiceMock->expects($this->any()) ->method('getId') ->will($this->returnValue(1));
         */
        
        $this->termTaxonomyMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(5));
        
        $this->taxonomyMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(10));
        
        $this->taxonomyMock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foobar'));
        
        $this->taxonomyMock->expects($this->any())
            ->method('getLanguage')
            ->will($this->returnValue($this->languageServiceMock));
        
        $this->languageManagerMock->expects($this->any())
            ->method('getLanguage')
            ->will($this->returnValue($this->languageServiceMock));
        
        $this->serviceLocatorMock->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->taxonomyManagerMock));
    }

    public function testGetTaxonomy()
    {
        $this->entityManagerMock->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->taxonomyMock));
        
        $this->assertEquals($this->taxonomyManagerMock, $this->sharedTaxonomyManager->getTaxonomy(10));
        $this->assertEquals($this->taxonomyManagerMock, $this->sharedTaxonomyManager->getTaxonomy(10));
    }

    /**
     * @expectedException \Taxonomy\Exception\InvalidArgumentException
     */
    public function testGetTaxonomyException()
    {
        $this->sharedTaxonomyManager->getTaxonomy('asdf');
    }

    /**
     * @expectedException \Taxonomy\Exception\NotFoundException
     */
    public function testTaxonomyNotFoundException()
    {
        $this->sharedTaxonomyManager->getTaxonomy(12345);
    }

    public function testFindTaxonomyByName()
    {
        $repositoryMock = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        
        $taxonomyTypeMock = $this->getMock('Taxonomy\Entity\TaxonomyType');
        $persistentCollectionMock = $this->getMock('Doctrine\Common\Collections\ArrayCollection');
        
        $persistentCollectionMock->expects($this->once())
            ->method('matching')
            ->will($this->returnValue($persistentCollectionMock));
        
        $persistentCollectionMock->expects($this->once())
            ->method('first')
            ->will($this->returnValue($this->taxonomyMock));
        
        $taxonomyTypeMock->expects($this->once())
            ->method('getTaxonomies')
            ->will($this->returnValue($persistentCollectionMock));
        
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(array(
            'name' => 'foobar'
        ))
            ->will($this->returnValue($taxonomyTypeMock));
        
        $this->entityManagerMock->expects(($this->any()))
            ->method('getRepository')
            ->will($this->returnValue($repositoryMock));
        
        $this->assertEquals($this->taxonomyManagerMock, $this->sharedTaxonomyManager->findTaxonomyByName('foobar', $this->languageServiceMock));
    }

    public function testGetTerm()
    {
        $this->termTaxonomyMock->expects($this->once())
            ->method('getTaxonomy')
            ->will($this->returnValue($this->taxonomyMock));
        
        $this->classResolverMock->expects($this->any())
            ->method('resolveClassName')
            ->will($this->returnValueMap(array(
            array(
                'Taxonomy\Entity\TermTaxonomyInterface',
                'Taxonomy\Entity\TermTaxonomy'
            ),
            array(
                'Taxonomy\Entity\TaxonomyInterface',
                'Taxonomy\Entity\Taxonomy'
            )
        )));
        
        $this->entityManagerMock->expects($this->atLeastOnce())
            ->method('find')
            ->will($this->returnValueMap(array(
            array(
                'Taxonomy\Entity\TermTaxonomy',
                $this->termTaxonomyMock->getId(),
                LockMode::NONE,
                null,
                $this->termTaxonomyMock
            ),
            array(
                'Taxonomy\Entity\Taxonomy',
                $this->taxonomyMock->getId(),
                LockMode::NONE,
                null,
                $this->taxonomyMock
            )
        )));
        
        $this->taxonomyManagerMock->expects($this->once())
            ->method('getTerm')
            ->will($this->returnValue($this->termServiceMock));
        
        $this->assertEquals($this->termServiceMock, $this->sharedTaxonomyManager->getTerm($this->termTaxonomyMock->getId()));
    }
    
    public function testGetCallback()
    {
        $this->assertNotNull($this->sharedTaxonomyManager->getCallback('footest'));
    }

    /**
     * @expectedException \Taxonomy\Exception\RuntimeException
     */
    public function testGetCallbackException()
    {
        $this->assertNotNull($this->sharedTaxonomyManager->getCallback('12345'));
    }
    
    public function testGetAllowedChildrenTypes()
    {
        $this->assertEquals(array('foobar'), $this->sharedTaxonomyManager->getAllowedChildrenTypes('foobarSimple'));
    }
}