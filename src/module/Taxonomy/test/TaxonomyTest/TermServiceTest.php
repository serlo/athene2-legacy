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
namespace TaxonomyTest;

use AtheneTest\Bootstrap;
class TermServiceTest extends \PHPUnit_Framework_TestCase
{
    
    protected $termService;
    
    public function setUp(){
        parent::setUp();
        $termService = Bootstrap::getServiceManager()->get('Taxonomy\Manager\SharedTaxonomyManager')->get(1)->get(11, 1);
        $this->termService = $termService;
    }
    
    public function testDelegation(){
        $params = array(
            'name' => 'Analysis',
            'slug' => 'analysis',
            'id' => 11
        );
        foreach($params as $param => $value){
            $method = 'get'.ucfirst($param);
            $this->assertEquals($value, $this->termService->$method());
        }
    }
}