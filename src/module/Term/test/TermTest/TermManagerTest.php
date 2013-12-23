<?php
/**
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace TermTest;

use Term\Manager\TermManager;
use AtheneTest\TestCase\ManagerTest;
use Term\Service\TermService;

/**
 * @codeCoverageIgnore
 */
class TermManagerTest extends ManagerTest
{

    /**
     *
     * @var TermManager
     */
    protected $termManager;

    public function setUp()
    {
        $this->termManager = new TermManager();
        $this->setManager($this->termManager);
        
        $this->prepareClassResolver([
            'Term\Entity\TermEntityInterface' => 'Term\Entity\TermEntity',
            'Term\Entity\TaxonomyTermInterface' => 'Term\Service\TermService'
        ]);
    }

    protected function mockTerm($id)
    {
        return $this->mockEntity('Term\Entity\TermEntity', $id);
    }

    protected function mockLanguage($id)
    {
        $language = $this->mockEntity('Language\Model\LanguageModelInterface', $id);
        $language->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($language));
        return $language;
    }

    protected function prepareFactory()
    {
        $this->prepareServiceLocator();
        
        $service = new TermService();
        
        $this->termManager->getServiceLocator()
            ->expects($this->once())
            ->method('get')
            ->with('Term\Service\TermService')
            ->will($this->returnValue($service));
        
        return $service;
    }

    /**
     * @expectedException \Term\Exception\InvalidArgumentException
     */
    public function testGetTermInvalidArgumentException()
    {
        $this->termManager->getTerm('asdf');
    }

    public function testGetTerm()
    {
        $service = $this->prepareFactory();
        $entity = $this->mockTerm(1);
        $language = $this->mockLanguage(1);
        
        $this->prepareFind('Term\Entity\TermEntity', 1, $entity);
        
        $this->assertSame($service, $this->termManager->getTerm(1));
    }

    public function testFindTermByName()
    {
        $service = $this->prepareFactory();
        $entity = $this->mockTerm(1);
        $language = $this->mockLanguage(1);
        
        $this->prepareFindOneBy('Term\Entity\TermEntity', array(
            'name' => 'foo',
            'language' => 1
        ), $entity);
        
        $this->assertSame($service, $this->termManager->findTermByName('foo', $language));
    }

    public function testFindTermBySlug()
    {
        $service = $this->prepareFactory();
        $entity = $this->mockTerm(1);
        $language = $this->mockLanguage(1);
        
        $this->prepareFindOneBy('Term\Entity\TermEntity', array(
            'slug' => 'foo',
            'language' => 1
        ), $entity);
        
        $this->assertSame($service, $this->termManager->findTermBySlug('foo', $language));
    }

    public function testCreateTerm()
    {
        $language = $this->mockLanguage(1);
        
        $this->prepareObjectManager();
        
        $this->termManager->getObjectManager()
            ->expects($this->once())
            ->method('persist');
        
        $this->assertNotNull($this->termManager->createTerm('a', 'b', $language));
    }
}