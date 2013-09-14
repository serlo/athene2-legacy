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

class SharedTaxonomyManagerTest extends \PHPUnit_Framework_TestCase
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
        $this->sharedTaxonomyManager = $sm->get('Taxonomy\Manager\SharedTaxonomyManager');
    }

    public function testGet ()
    {
        $this->assertEquals(1, $this->sharedTaxonomyManager->get(1)
            ->getId());
    }

    public function testGetByName ()
    {
        $this->assertEquals(1, $this->sharedTaxonomyManager->get('topic', 1)
            ->getId());
    }

    public function testGetAllowedChildrenTypes ()
    {
        $this->sharedTaxonomyManager->appendConfig(array(
            'types' => array(
                'foobar' => array(
                    'options' => array()
                ),
                'foobar1' => array(
                    'options' => array('allowed_parents' => array( 'foobar') )
                ),
            )
        ));
        $this->assertEquals(array(
            'foobar1',
        ), $this->sharedTaxonomyManager->getAllowedChildrenTypes('foobar'));
    }

    public function testGetTerm ()
    {
        $term = $this->sharedTaxonomyManager->getTerm(11);
        $this->assertEquals(11, $term->getId());
        $this->assertEquals(11, $this->sharedTaxonomyManager->getTerm($term->getEntity())
            ->
        getId());
    }

    public function testGetByInstance ()
    {
        $instance = $this->sharedTaxonomyManager->get(1, 1);
        $this->assertEquals($instance, $this->sharedTaxonomyManager->get($instance, 1));
        $this->assertEquals($instance, $this->sharedTaxonomyManager->get($instance->getEntity(), 1));
    }
}