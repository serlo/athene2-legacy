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
namespace BlogTest\Controller;

use Blog\Controller\BlogController;
use AtheneTest\TestCase\ControllerTestCase;

abstract class BlogControllerTest extends ControllerTestCase
{

    public function setUp()
    {
        $this->controller = new BlogController();
        $blogManager = $this->getMock('Blog\Manager\BlogManager');
        
        $this->controller->setBlogManager($blogManager);
        $this->preparePluginManager();
    }

    public function testIndexAction()
    {
        $languageManager = $this->prepareLanguageFromRequest(1, 'de');
        $this->controller->setLanguageManager($languageManager);
        
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $this->controller->indexAction());
    }

    public function testViewPostAction()
    {
        $this->prepareGetBlog();
        
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $this->controller->viewPostAction());
    }

    public function testViewAllAction()
    {
        $this->prepareGetBlog();
        
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $this->controller->viewAllAction());
    }

    public function testViewAction()
    {
        $this->prepareGetBlog();
        
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $this->controller->viewAction());
    }

    protected function prepareGetBlog()
    {
        $postManager = $this->getMock('Blog\Manager\PostManager');
        
        $this->controller->getBlogManager()
            ->expects($this->once())
            ->method('getBlog')
            ->with()
            ->will($this->returnValue($postManager));
    }
}