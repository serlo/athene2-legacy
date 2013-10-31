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
use Uuid\Entity\Uuid;

/**
 * @codeCoverageIgnore
 */
class UserTest extends Model
{

    public function setUp()
    {
        $this->setObject(new \User\Entity\User());
    }

    protected function getData()
    {
        return array(
            'email' => 'asdf',
            'username' => 'asdf',
            'password' => '12345',
            'lastname' => 'a',
            'givenname' => 'b',
            'logins' => 10,
            'lastLogin' => 12345,
            'date' => 1234,
            'gender' => 'a',
            'adsEnabled' => false,
        );
    }
}