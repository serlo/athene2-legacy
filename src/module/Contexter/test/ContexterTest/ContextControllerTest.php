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
namespace ContexterTest;

use Contexter\Controller\ContextController;
use AtheneTest\TestCase\ControllerTestCase;

class ContextControllerTest extends ControllerTestCase
{

    protected $controller;

    public function setUp()
    {
        $this->controller = new ContextController();
        $objectManager = $this->mockEntityManager();
        $contextManager = $this->mock('Contexter\Manager\ContextManager');
        $contextRouter = $this->mock('Contexter\Router\Router');
        $request = $this->getMock('');
        
        $this->controller->setContextManager($contextManager);
        $this->controller->setRouter($contextRouter);
    }

    public function testManageAction()
    {
        $this->controller->getContextManager()
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([]));
        
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $this->controller->manageAction());
    }

    public function testAddAction()
    {
        
    }
}