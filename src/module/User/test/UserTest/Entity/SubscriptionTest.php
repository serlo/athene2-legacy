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
namespace UserTest\Entity;

use AtheneTest\TestCase\Model;
use Uuid\Entity\Uuid;
use User\Entity\User;
use User\Entity\Subscription;

/**
 * @codeCoverageIgnore
 */
class SubscriptionTest extends Model
{

    /**
     *
     * @return Subscription
     */
    public function getObject()
    {
        return parent::getObject();
    }

    public function setUp()
    {
        $this->setObject(new Subscription());
    }

    protected function getData()
    {
        $uuid = $this->getMock('Uuid\Entity\Uuid');
        return array(
            'notifyMailman' => true,
            'subscriber' => $this->getMock('User\Entity\User'),
            'subscribedObject' => $this->getMock('Uuid\Entity\Uuid'),
            'id' => NULL
        );
    }
}