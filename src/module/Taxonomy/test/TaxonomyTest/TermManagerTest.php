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

use AtheneTest\TestCase\ObjectManagerTestCase;
use AtheneTest\Bootstrap;
use Doctrine\DBAL\LockMode;
use Taxonomy\Entity\TermTaxonomy;
use Uuid\Entity\Uuid;
use Term\Service\TermService;
use Taxonomy\Entity\Taxonomy;
use Taxonomy\Entity\TaxonomyType;
use TaxonomyTest\Fake\TermTaxonomyRepositoryFake;
use Term\Entity\Term;

class TermManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var TermService
     */
    protected $termManager;

    public function setUp ()
    {
        parent::setUp();
        $sm = Bootstrap::getServiceManager();
        $this->termManager = $sm->get('Taxonomy\Manager\SharedTaxonomyManager')->get('topic', 1);
    }

    public function testGetById ()
    {
        $this->assertEquals(11, $this->termManager->get(11)
            ->getId());
    }

    /*public function testGetById ()
    {
        $this->assertEquals(2, $this->termManager->get(2)
            ->getId());
        $this->assertEquals(2, $this->termManager->get('2')
            ->getId());
    }*/
}