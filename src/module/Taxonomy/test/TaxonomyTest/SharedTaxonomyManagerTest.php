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

use TaxonomyTest\Fake\TaxonomyRepositoryFake;
use Taxonomy\Manager\SharedTaxonomyManager;
use AtheneTest\Bootstrap;
use Taxonomy\Entity\TermTaxonomy;
use Language\Entity\Language;
use Uuid\Entity\Uuid;
use Language\Service\LanguageService;
use TaxonomyTest\Fake\TaxonomyTypeRepositoryFake;
use Taxonomy\Entity\Taxonomy;
use Doctrine\DBAL\LockMode;
use Taxonomy\Entity\TaxonomyType;

class SharedTaxonomyManagerTest extends TaxonomyTestCase
{

    /**
     *
     * @var SharedTaxonomyManager
     */
    protected $sharedTaxonomyManager;

    protected $languageService;

    public function setUp ()
    {
        parent::setUp();
        
        
        $sm = Bootstrap::getServiceManager();
        $em = $sm->get('Doctrine\Orm\EntityManager');
        
        $languageEntity = new Language();
        $languageEntity->setId(1);
        $language = new LanguageService();
        $language->setEntity($languageEntity);
        $this->languageService = $language;
        
        $map = array(
            array(
                'Taxonomy\Entity\TermTaxonomy',
                2,
                LockMode::NONE,
                null,
                (new TermTaxonomy())->setUuid((new Uuid())->setId(2))
            ),
            array(
                'Taxonomy\Entity\Taxonomy',
                6,
                LockMode::NONE,
                null,
                (new Taxonomy())->setId(6)->setType((new TaxonomyType())->setId(6)->setName('foobar'))
            )
        );
        
        $em->expects($this->any())
            ->method('find')
            ->will($this->returnValueMap($map));
        
        $repository1 = new TaxonomyRepositoryFake();
        $repository2 = new TaxonomyTypeRepositoryFake();
        
        $map2 = array(
            array(
                'Taxonomy\Entity\Taxonomy',
                $repository1
            ),
            array(
                'Taxonomy\Entity\TaxonomyType',
                $repository2
            )
        );
        
        $em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($map2));
        
        $this->sharedTaxonomyManager = $sm->get('Taxonomy\Manager\SharedTaxonomyManager');
    }

    public function testGetTaxonomyManager ()
    {
        $this->assertEquals(6, $this->sharedTaxonomyManager->get(6, $this->languageService)
            ->getId());
        $this->assertEquals(6, $this->sharedTaxonomyManager->get('foobar', $this->languageService)
            ->getId());
    }
}