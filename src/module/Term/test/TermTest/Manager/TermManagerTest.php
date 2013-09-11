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
use AtheneTest\TestCase\ObjectManagerTestCase;

class TermManagerTest extends ObjectManagerTestCase
{
    private $termManager;
    
    public function setUp(){
        parent::setUp();
        
        $sm = AtheneBootstrap::getServiceManager();
        $termManager = new TermManager();
        
        $termManager->setClassResolver($sm->get('ClassResolver\ClassResolver'));
        $termManager->setLanguageManager($sm->get('Language\Manager\LanguageManager'));
        $termManager->setServiceLocator($sm);
        $termManager->setObjectManager($sm->get('doctrine.entitymanager.orm_default'));
        
        $repository = new TermRepositoryFake();
        $this->injectEntityRepository($repository);
        
        $this->termManager = $termManager;
    }
    
    public function testGet(){
        $this->assertEquals($this->termManager->get('foobar')->getId(), 1) ;
    }
}