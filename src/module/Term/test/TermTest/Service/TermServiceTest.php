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
namespace TermTest\Service;

use Term\Service\TermService;
use TermTest\Bootstrap;

class TermServiceTest extends \PHPUnit_Framework_TestCase
{
    private $termService, $languageEntity;
    
    public function setUp(){
        $sm = Bootstrap::getServiceManager();
        $termService = new TermService();
        
        $termService->setObjectManager($sm->get('doctrine.entitymanager.orm_default'));
        $termEntity = new \Term\Entity\Term();
        $languageEntity = $this->languageEntity = new \Language\Entity\Language();
        $termEntity->setName('Test');
        $termEntity->setSlug('test');
        $termEntity->setId('1');
        $termEntity->setLanguage($languageEntity);
        $termService->setEntity($termEntity);
        
        $this->termService = $termService;
    }
    
    public function testDelegation(){
        $this->assertEquals($this->termService->getName(), 'Test');
        $this->assertEquals($this->termService->getSlug(), 'test');
        $this->assertEquals($this->termService->getId(), '1');
        $this->assertEquals($this->termService->getLanguage(), $this->languageEntity);
    }
}