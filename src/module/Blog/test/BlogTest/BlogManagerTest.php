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
namespace BlogTest;

use AtheneTest\TestCase\ManagerTest;
use Blog\Manager\BlogManager;
use Blog\Manager\PostManager;

abstract class BlogManagerTest extends ManagerTest
{

    protected $blogManager;

    public function setUp()
    {
        $this->blogManager = new BlogManager();
        $this->setManager($this->blogManager);
        
        $this->prepareClassResolver([
            'Blog\Entity\PostInterface' => 'Blog\Entity\Post',
            'Blog\Service\PostServiceInterface' => 'Blog\Service\PostService',
            'Blog\Manager\PostManagerInterface' => 'Blog\Manager\PostManager'
        ]);
    }

    protected function prepareSharedTaxonomyManager()
    {
        $mock = $this->getMock('Taxonomy\Manager\TaxonomyManager');
        $this->blogManager->setTaxonomyManager($mock);
        return $mock;
    }

    protected function prepareCreateService($return)
    {
        $serviceManager = $this->prepareServiceLocator();
        $serviceManager->expects($this->once())
            ->method('get')
            ->with('Blog\Manager\PostManager')
            ->will($this->returnValue($return));
        
        return $serviceManager;
    }

    protected function mockTermService($id)
    {
        $mock = $this->getMock('Taxonomy\Service\TermService');
        $mock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        return $mock;
    }

    protected function mockTaxonomyManager($id)
    {
        $mock = $this->getMock('Taxonomy\Manager\TaxonomyManager');
        $mock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        return $mock;
    }

    protected function mockLanguage()
    {
        return $this->getMock('Language\Entity\LanguageInterface');
    }

    public function testGetBlog()
    {
        $service = new PostManager();
        $termService = $this->mockTermService(1);
        
        $this->prepareCreateService($service);
        
        $this->prepareSharedTaxonomyManager()
            ->expects($this->once())
            ->method('getTerm')
            ->with(1)
            ->will($this->returnValue($termService));
        
        $this->assertSame($service, $this->blogManager->getBlog('1'));
    }

    public function testFindAllBlogs()
    {
        $service = new PostManager();
        $taxonomyManager = $this->mockTaxonomyManager(1);
        $sharedTaxonomyManager = $this->prepareSharedTaxonomyManager();
        $termService = $this->mockTermService(1);
        $language = $this->mockLanguage();
        
        $this->prepareCreateService($service);
        
        $sharedTaxonomyManager->expects($this->once())
            ->method('findTaxonomyByName')
            ->with('blog', $language)
            ->will($this->returnValue($taxonomyManager));
        
        $taxonomyManager->expects($this->once())
            ->method('getChildren')
            ->will($this->returnValue([
            $this->mockTermService(1),
            $this->mockTermService(1),
            $this->mockTermService(1)
        ]));        
        
        $sharedTaxonomyManager->expects($this->atLeastOnce())
            ->method('getTerm')
            ->with(1)
            ->will($this->returnValue($termService));
        
        $this->assertEquals([
            $service,
            $service,
            $service
        ], $this->blogManager->findAllBlogs($language));
    }
}