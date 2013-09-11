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
namespace TermTest\Manager;

use AtheneTest\Bootstrap as AtheneBootstrap;
use Term\Manager\TermManager;

class TermManagerTest extends \PHPUnit_Framework_TestCase
{
    private $termManager;
    
    public function setUp(){
        $sm = AtheneBootstrap::getServiceManager();
        $termManager = new TermManager(); //$sm->get('Term\Manager\TermManager');// new TermManager();        
        //$mock = $this->getMock('Term\Service\TermService');
        
        $termManager->setClassResolver($sm->get('ClassResolver\ClassResolver'));
        $termManager->setLanguageManager($sm->get('Language\Manager\LanguageManager'));
        $termManager->setServiceLocator($sm);
        $termManager->setObjectManager($sm->get('doctrine.entitymanager.orm_default'));
        
        $this->termManager = $termManager;
    }
    
    public function testGet(){
        $this->assertEquals($this->termManager->get('mathe')->getId(), 11) ;
    }
}