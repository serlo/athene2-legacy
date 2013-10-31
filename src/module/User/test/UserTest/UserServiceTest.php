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

use AtheneTest\TestCase\Model;
use User\Service\UserService;
use User\Entity\User;

/**
 * @codeCoverageIgnore
 */
class UserServiceTest extends Model
{
    protected $userService;
    
    public function setUp(){
        $this->userService = new UserService();
        
        $entity = new User();
        $this->userService->setEntity($entity);
        
        $this->setObject($this->userService);
    }
    
	/* (non-PHPdoc)
     * @see \AtheneTest\TestCase\Model::getData()
     */
    protected function getData ()
    {
        return array(
            'logs' => array(),
            'email' => 'asdf',
            'username' => 'herlp',
            'password' => 'secret',
            'logins' => 3,
            'lastLogin' => new \DateTime("NOW"),
            'date' => new \DateTime("NOW"),
            'givenname' => 'peter',
            'lastname' => 'dichtl',
            'gender' => 'm',
        );
    }

}