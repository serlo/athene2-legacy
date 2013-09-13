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
namespace UserTest;

use User\Manager\UserManager;
use AtheneTest\Bootstrap as AtheneBootstrap;
use AtheneTest\TestCase\ObjectManagerTestCase;
use User\Entity\User;
use AtheneTest\Bootstrap;

class UserManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var UserManager
     */
    protected $userManager;
    
    private $user;

    public function setUp ()
    {
        parent::setUp();
        
        $sm = AtheneBootstrap::getServiceManager();
        $userManager = $sm->get('User\Manager\UserManager');
        $this->userManager = $userManager;
    }

    public function testGetById ()
    {
        $this->assertEquals('1', $this->userManager->get(1)
            ->getId());
    }

    public function testGetByUsername ()
    {
        $this->assertEquals('1', $this->userManager->get('arekkas')
            ->getId());
    }

    public function testGetByEmail ()
    {
        $this->assertEquals('1', $this->userManager->get('aeneas@q-mail.me')
            ->getId());
    }

    public function testRepositories ()
    {
        $this->assertNotNull($this->userManager->findAllRoles());
        $this->assertNotNull($this->userManager->findAllUsers());
    }

    public function testCreateAndRemove ()
    {        
        $entity = $this->userManager->create(array(
            'username' => 'test',
            'email' => 'test@test.de',
            'password' => 'foobar',
            'language' => 1,
            'logins' => 0,
            'gender' => 'n',
            'ads_enabled' => 1,
            'removed' => 0
        ));
        
        $this->assertInstanceOf('User\Service\UserServiceInterface', $entity);
        $this->assertEquals('test', $entity->getName());
        
        $id = $entity->getId();
        $this->userManager->purge($entity);
        $this->assertEquals(false, $this->userManager->has($id));
    }
}