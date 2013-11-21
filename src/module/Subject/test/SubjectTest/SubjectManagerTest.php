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
namespace SubjectTest;

use Subject\Manager\SubjectManager;

class SubjectManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $subjectManager, $sharedTaxonomyManagerMock, $termServiceMock, $termTaxonomyMock, $languageManagerMock, $languageServiceMock, $classResolverMock, $serviceLocatorMock; // , $serviceLocatorMock;
    public function setUp()
    {
        $this->subjectManager = new SubjectManager(array(
            'instances' => array(
                array(
                    'name' => 'foobar',
                    'language' => 'de',
                    'plugins' => array()
                )
            )
        ));
        
        $this->sharedTaxonomyManagerMock = $this->getMock('Taxonomy\Manager\SharedTaxonomyManager');
        $this->termServiceMock = $this->getMock('Taxonomy\Service\TermService');
        $this->termTaxonomyMock = $this->getMock('Taxonomy\Entity\TaxonomyTerm');
        $this->languageManagerMock = $this->getMock('Language\Manager\LanguageManager');
        $this->languageServiceMock = $this->getMock('Language\Service\LanguageService');
        $this->classResolverMock = $this->getMock('ClassResolver\ClassResolver');
        $this->serviceLocatorMock = $this->getMock('Zend\ServiceManager\ServiceManager');
        $this->subjectServiceMock = $this->getMock('Subject\Service\SubjectService');
        
        $this->termServiceMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(3));
        
        $this->termTaxonomyMock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foobar'));
        
        $this->termTaxonomyMock->expects($this->any())
            ->method('getLanguage')
            ->will($this->returnValue($this->languageServiceMock));
        
        $this->termServiceMock->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($this->termTaxonomyMock));
        
        $this->languageManagerMock->expects($this->any())
            ->method('getLanguage')
            ->will($this->returnValue($this->languageServiceMock));
        
        $this->languageServiceMock->expects($this->any())
            ->method('getCode')
            ->will($this->returnValue('de'));
        
        $this->languageServiceMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        
        $this->serviceLocatorMock->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->subjectServiceMock));
        
        $this->classResolverMock->expects($this->any())
            ->method('resolveClassName')
            ->will($this->returnValue('Subject\Service\SubjectService'));
        
        $this->subjectManager->setSharedTaxonomyManager($this->sharedTaxonomyManagerMock);
        $this->subjectManager->setLanguageManager($this->languageManagerMock);
        $this->subjectManager->setClassResolver($this->classResolverMock);
        $this->subjectManager->setServiceLocator($this->serviceLocatorMock);
        $this->sharedTaxonomyManagerMock->expects($this->any())
            ->method('getTerm')
            ->will($this->returnValue($this->termServiceMock));
    }

    public function testFindSubjectByString()
    {
        $taxonomyManagerMock = $this->getMock('Taxonomy\Manager\TaxonomyManager');
        
        $this->sharedTaxonomyManagerMock->expects($this->any())
            ->method('findTaxonomyByName')
            ->will($this->returnValue($taxonomyManagerMock));
        
        $taxonomyManagerMock->expects($this->once())
            ->method('findTermByAncestors')
            ->will($this->returnValue($this->termServiceMock));
        
        $this->assertEquals($this->subjectServiceMock, $this->subjectManager->findSubjectByString('foobar', $this->languageServiceMock));
    }

    public function testFindSubjectsByLanguage()
    {
        
    }

    public function testGetSubject()
    {
        $this->assertEquals($this->subjectServiceMock, $this->subjectManager->getSubject(3));
    }
}