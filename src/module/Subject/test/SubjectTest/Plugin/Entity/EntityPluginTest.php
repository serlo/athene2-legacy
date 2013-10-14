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
namespace SubjectTest\Plugin\Entity;

use Subject\Plugin\Entity;
use SubjectTest\Plugin\AbstractPluginTest;
use Doctrine\Common\Collections\ArrayCollection;

class EntityPluginTest extends AbstractPluginTest
{

    protected $entityPlugin, $entityManagerMock, $objectManagerMock, $termServiceMock;

    public 

    function setUp()
    {
        parent::setUp();
        $this->entityPlugin = new Entity\EntityPlugin();
        
        $this->entityManagerMock = $this->getMock('Entity\Manager\EntityManager');
        $this->objectManagerMock = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->termServiceMock = $this->getMock('Taxonomy\Service\TermService');
        
        $this->entityPlugin->setObjectManager($this->objectManagerMock);
        $this->entityPlugin->setEntityManager($this->entityManagerMock);
        $this->entityPlugin->setSubjectService($this->subjectServiceMock);
    }

    private function GetUnrevisedEntitiesDeprecated()
    {
        $this->subjectServiceMock->expects($this->once())
            ->method('getTermService')
            ->will($this->returnValue($this->termServiceMock));
        
        $collection = new ArrayCollection();
        
        for ($i = 0; $i <= 3; $i ++) {
            $entityService = $this->getMock('Entity\Service\EntityService', array('repository', 'link', 'getScopesForPlugin'));
            $entityService->expects($this->any())
                ->method('getId')
                ->will($this->returnValue($i));
            
            $entityService->expects($this->atLeastOnce())
                ->method('getScopesForPlugin')
                ->will($this->returnValueMap(array(
                array(
                    'repository',
                    array('repository')
                ),
                array(
                    'link',
                    array('link')
                )
            )));
            
            $repositoryPluginMock = $this->getMock('LearningResource\Plugin\Repository\RepositoryPlugin');
            $linkPluginMock = $this->getMock('LearningResource\Plugin\Link\LinkPlugin');
            
            $repositoryPluginMock->expects($this->atLeastOnce())
                ->method('isUnrevised')
                ->will($this->returnValue(true));
            
            $entityService->expects($this->atLeastOnce())
                ->method('repository')
                ->will($this->returnValue($repositoryPluginMock));
            
            //$entityService->expects($this->atLeastOnce())
            //    ->method('link')
            //    ->will($this->returnValue($linkPluginMock));
            
            $collection->add($entityService);
        }
        
        $this->termServiceMock->expects($this->once())
            ->method('getAssociated')
            ->will($this->returnValue($collection));
        
        $this->entityPlugin->getUnrevisedEntities();
        
        $this->assertEquals($this->subjectServiceMock, $this->entityPlugin->getSubjectService());
    }
}