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
namespace SubjectTest;

use Subject\Manager\SubjectManager;
use AtheneTest\Bootstrap;

class SubjectManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 
     * @var SubjectManager
     */
    protected $subjectManager;
    
    public function setUp(){
        $this->subjectManager = Bootstrap::getServiceManager()->get('Subject\Manager\SubjectManager');
    }
    
    public function testGet(){
        $this->assertEquals(25, $this->subjectManager->getSubject('mathe', 1)->getId());
    }
    
    public function testGetSubjectsWithLanguage(){
        $this->assertNotEmpty($this->subjectManager->getSubjectsWithLanguage(1));
    }
    
    public function testHas(){
        $this->subjectManager->getSubject('mathe', 1);
        $this->assertEquals(true, $this->subjectManager->hasSubject(25));
    }
}