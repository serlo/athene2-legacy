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
use Blog\Manager\PostManager;
use Taxonomy\Service\TermService;
use Blog\Service\PostService;
use DateTime;
use Taxonomy\Entity\TaxonomyTerm;
use User\Entity\User;
use Blog\Entity\Post;

class PostManagerTest extends ManagerTest
{

    protected $postManager;

    public function setUp()
    {
        $this->postManager = new PostManager();
        $termService = new TermService();
        
        $this->postManager->setTermService($termService);
        $this->setManager($this->postManager);
        
        $this->prepareClassResolver([
            'Blog\Entity\PostInterface' => 'Blog\Entity\Post',
            'Blog\Service\PostServiceInterface' => 'Blog\Service\PostService'
        ]);
    }

    protected function prepareTermService(array $data)
    {
        $mock = $this->getMock('Taxonomy\Service\TermService');
        
        foreach ($data as $key => $value) {
            $mock->expects($this->any())
                ->method('get' . ucfirst($key))
                ->will($this->returnValue($value));
        }
        $this->getManager()->setTermService($mock);
    }

    protected function prepareCreateService()
    {
        $service = new PostService();
        $serviceManager = $this->prepareServiceLocator();
        $serviceManager->expects($this->once())
            ->method('get')
            ->with('Blog\Service\PostService')
            ->will($this->returnValue($service));
        
        return $service;
    }

    protected function prepareGetPost()
    {
        $service = $this->prepareCreateService();
        $entity = $this->mockEntity('Blog\Entity\Post', 1);
        
        $this->prepareFind('Blog\Entity\Post', 1, $entity);
        return $service;
    }

    public function testProperties()
    {
        $this->prepareTermService([
            'name' => 'foo',
            'slug' => 'foo-slug',
            'id' => 1,
            'associated' => []
        ]);
        
        $this->assertEquals('foo', $this->postManager->getName());
        $this->assertEquals('foo-slug', $this->postManager->getSlug());
        $this->assertEquals(1, $this->postManager->getId());
        $this->assertEquals([], $this->postManager->findAllPosts());
    }

    public function testGetPost()
    {
        $service = $this->prepareGetPost();
        $this->assertSame($service, $this->postManager->getPost(1));
    }

    public function testUpdatePost()
    {
        $service = $this->prepareCreateService();
        $entity = new Post();
        
        $this->prepareFind('Blog\Entity\Post', 1, $entity);
        
        $entity->setAuthor(new User());
        $entity->setCategory(new TaxonomyTerm());
        $service->setObjectManager($this->prepareObjectManager(false));
        
        $service->getObjectManager()
            ->expects($this->once())
            ->method('persist');
        
        $this->assertSame($this->postManager, $this->postManager->updatePost(1, 'name', 'content', new DateTime('now')));
    }

    public function testCreatePost()
    {
        $this->prepareObjectManager();
        $this->prepareCreateService();
        
        $uuidManager = $this->getMock('Uuid\Manager\UuidManager');
        $termService = $this->getMock('Taxonomy\Service\TermService');
        $author = new User();
        
        $this->postManager->setUuidManager($uuidManager);
        $this->postManager->setTermService($termService);
        
        $this->assertInstanceOf('Blog\Service\PostServiceInterface', $this->postManager->createPost($author, 'a', 'b', new DateTime('now')));
    }
}