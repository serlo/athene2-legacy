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
namespace CommonTest;

use CommonTest\Fake\InstanceManager;
use CommonTest\Fake\InstanceFake;

class InstanceManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return InstanceManager
     */
    protected $instanceManager;
    
    protected $instance;
    
    public function setUp(){
        $this->instanceManager = new InstanceManager();
    }
    
    public function testAddInstance(){
        $this->instance = new InstanceFake();
        $this->instance->setId(1);
        $this->instanceManager->add(1, $this->instance);        
        $this->assertEquals(true, $this->instanceManager->has(1));
    }
    
    public function testGetInstance(){
        $this->instance = new InstanceFake();
        $this->instance->setId(1);
        $this->instanceManager->add(1, $this->instance);        
        $this->assertEquals($this->instance, $this->instanceManager->get(1));
    }
    
    public function testRemoveInstance(){
        $this->instance = new InstanceFake();
        $this->instance->setId(1);
        $this->instanceManager->add(1, $this->instance);        
        $this->instanceManager->remove(1);   
        $this->assertEquals(false, $this->instanceManager->has(1));
    }
}