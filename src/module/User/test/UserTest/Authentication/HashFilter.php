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


use User\Authentication\HashFilter;
use User\Authentication\HashService;
/**
 * @codeCoverageIgnore
 */
class HashFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $hashFilter;
    
    public function setUp(){
        $this->hashFilter = new HashFilter();
        $this->hashService = new HashService();
    }
    
    public function test(){
        $password = "12345678";
        $encrypted = ($this->hashFilter->hashPassword($password));
        $this->assertEquals($encrypted, $this->hashFilter->hashPassword($password, $this->hashFilter->findSalt($encrypted)));
    }
}