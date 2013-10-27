<?php
namespace UserTest;

use AtheneTest\TestCase\Model;
use User\Service\UserService;
use User\Entity\User;

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