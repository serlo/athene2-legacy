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

use CommonTest\Fake\ConfigurableFake;

class ConfigurableTest extends \PHPUnit_Framework_TestCase
{

    protected $config;

    public function setUp ()
    {
        $this->config = new ConfigurableFake();
    }

    public function testSetConfig ()
    {
        $this->config->setConfig(array(
            'd' => 4,
            'a' => 1,
            'b' => array(
                100
            )
        ));
        
        $this->assertEquals(array(
            'a' => 1,
            'd' => 4,
            'b' => array(
                1,
                2,
                100
            ),
            'c' => array(
                'a' => 1
            )
        ), $this->config->getConfig());
    }

    public function testAppendConfig ()
    {
        $this->config->setConfig(array(
            'd' => 4,
            'a' => 1,
            'b' => array(
                100
            )
        ));
        $this->config->appendConfig(array('x' => 1));
        
        $this->assertEquals(array(
            'a' => 1,
            'd' => 4,
            'x' => 1,
            'b' => array(
                1,
                2,
                100
            ),
            'c' => array(
                'a' => 1
            )
        ), $this->config->getConfig());
    }

    public function testGetOption ()
    {
        $this->config->setConfig(array(
            'd' => 4,
            'a' => 1,
            'b' => array(
                100
            )
        ));    
        $this->assertEquals(4, $this->config->getOption('d'));
    }
}