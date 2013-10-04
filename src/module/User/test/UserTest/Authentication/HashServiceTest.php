<?php
namespace UserTest\Authentication;

use User\Authentication\HashService;
class HashServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $hashService;
    
    public function setUp(){
        $this->hashService = new HashService();
    }
    
    public function test(){
        $password = "12345678";
        $encrypted = $this->hashService->hashPassword($password);
        $this->assertEquals($encrypted, $this->hashService->hashPassword($password, $this->hashService->findSalt($encrypted)));
    }
}