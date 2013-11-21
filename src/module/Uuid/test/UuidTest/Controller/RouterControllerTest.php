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
namespace UuidTest\Controller;

use AtheneTest\Controller\Athene2ApplicationTestCase;

/**
 * @codeCoverageIgnore
 */
class RouterControllerTest extends Athene2ApplicationTestCase
{

    protected $controller;

    public function setUp()
    {
        parent::setUp();
        $this->controller = $this->getApplicationServiceLocator()->get('Uuid\Controller\RouterController');
        $uuidRouter = $this->getMock('Uuid\Router\UuidRouter');
        $this->controller->setUuidRouter($uuidRouter);
    }

    public function testAssemble()
    {
        $this->controller->getUuidRouter()
            ->expects($this->once())
            ->method('assemble')
            ->will($this->returnValue('/'));
        
        $this->dispatch('/uuid/route/1');
        $this->assertResponseStatusCode(302);
    }
}