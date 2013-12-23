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
namespace AliasTest;

use Alias\AliasManager;
use ClassResolver\ClassResolver;
use Alias\Entity\Alias;
use Uuid\Entity\Uuid;
use AtheneTest\TestCase\ManagerTest;

class AliasManagerTest extends ManagerTest
{

    protected $aliasManager;

    public function setUp()
    {
        $this->aliasManager = new AliasManager();
        $this->setManager($this->aliasManager);
        
        $this->prepareClassResolver([
            'Alias\Entity\AliasInterface' => 'Alias\Entity\Alias'
        ]);
    }

    protected function setUpRepository(array $criteria, array $data)
    {
        $entity = new Alias();
        
        $this->prepareFindOneBy('Alias\Entity\Alias', $criteria, $entity);
        
        foreach ($data as $key => $value) {
            $key = 'set' . ucfirst($key);
            $entity->$key($value);
        }
        
        return $entity;
    }

    protected function fakeLanguage($id)
    {
        return $this->mockEntity('Language\Entity\LanguageInterface', $id);
    }

    public function testFindSourceByAlias()
    {
        $language = $this->fakeLanguage(1);
        
        $this->setUpRepository([
            'alias' => 'foo',
            'language' => 1
        ], [
            'source' => 'bar'
        ]);
        
        $this->assertSame('bar', $this->aliasManager->findSourceByAlias('foo', $language));
    }

    public function testFindAliasBySource()
    {
        $language = $this->fakeLanguage(1);
        
        $this->setUpRepository([
            'source' => 'foo',
            'language' => 1
        ], [
            'alias' => 'bar'
        ]);
        
        $this->assertSame('bar', $this->aliasManager->findAliasBySource('foo', $language));
    }

    public function testCreateAlias()
    {
        $entity = new Alias();
        $language = $this->fakeLanguage(1);
        $uuid = $this->getMock('Uuid\Entity\Uuid');
        
        $this->prepareFindOneBy('Alias\Entity\Alias', [
            'uuid' => 1
        ], null);
        
        $uuid->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));
        $this->aliasManager->getObjectManager()
            ->expects($this->once())
            ->method('persist');
        $language->expects($this->once())
            ->method('getEntity')
            ->will($this->returnValue($this->fakeLanguage(1)));
        
        $this->assertNotNull($this->aliasManager->createAlias('foo', 'bar', 'bar-1', $uuid, $language));
    }
}