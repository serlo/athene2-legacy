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
namespace UserTest\Authentication;

use User\Authentication\HashService;

/**
 * @codeCoverageIgnore
 */
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