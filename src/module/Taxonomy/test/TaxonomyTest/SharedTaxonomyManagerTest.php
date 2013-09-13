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
use AtheneTest\TestCase\ObjectManagerTestCase;
use Term\Entity\Term;

class SharedTaxonomyManagerTest extends ObjectManagerTestCase
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
        $em = $sm->get('Doctrine\ORM\EntityManager');
        
        $languageEntity = new Language();
        $languageEntity->setId(1);
        $language = new LanguageService();
        $language->setEntity($languageEntity);
        $this->languageService = $language;
        
        $map = array(
            array(
                'Language\Entity\Language',
                1,
                LockMode::NONE,
                null,
                (new Language())->setId(1)
            ),
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
                (new Taxonomy())->setId(6)->setType((new TaxonomyType())->setId(6)
                    ->setName('foobar'))
            ),
            array(
                'Taxonomy\Entity\TermTaxonomy',
                3,
                LockMode::NONE,
                null,
                (new TermTaxonomy())->setId((new Uuid())->setId(3))
                    ->setTaxonomy(
                        (new Taxonomy())->setId(6)
                                        ->setType(
                                                (new TaxonomyType())
                                                ->setName('foobar'))
                                        )
                    ->setTerm((new Term())->setName('lulzhard'))
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
        $this->sharedTaxonomyManager->setObjectManager($em);
        
        $this->sharedTaxonomyManager->setConfig(array(
            'types' => array(
                'foobar' => array(
                    'options' => array(
                        'allowed_parents' => array(
                            'foobar1'
                        )
                    )
                ),
                'foobar1' => array(
                    'options' => array()
                ),
                'foobar2' => array(
                    'options' => array(
                        'allowed_parents' => array(
                            'foobar1'
                        )
                    )
                )
            )
        ));
    }

    public function testGet ()
    {
        $this->assertEquals(6, $this->sharedTaxonomyManager->get(6, $this->languageService)
            ->getId());
    }

    public function testGetByName ()
    {
        $this->assertEquals(1, $this->sharedTaxonomyManager->get('foobar', $this->languageService)
            ->getId());
    }

    public function testGetAllowedChildrenTypes ()
    {
        $this->assertEquals(array(
            'foobar',
            'foobar2'
        ), $this->sharedTaxonomyManager->getAllowedChildrenTypes('foobar1'));
    }

    public function testGetTerm ()
    {
        $term = $this->sharedTaxonomyManager->getTerm(3);
        $this->assertEquals(3, $term->getId());
        $this->assertEquals(3, $this->sharedTaxonomyManager->getTerm($term->getEntity())
            
            ->getId());
    }

    public function testGetByInstance ()
    {
        $instance = $this->sharedTaxonomyManager->get(6, $this->languageService);
        $this->assertEquals($instance, $this->sharedTaxonomyManager->get($instance, $this->languageService));
        $this->assertEquals($instance, $this->sharedTaxonomyManager->get($instance->getEntity(), $this->languageService));
    }

    public function testHas ()
    {
        $e = $this->sharedTaxonomyManager->get(6, $this->languageService);
        $this->assertEquals(true, $e->getId());
    }
}