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

use AtheneTest\TestCase\Model;
use Taxonomy\Entity\Taxonomy;
use Language\Entity\LanguageEntity;
use Taxonomy\Entity\TaxonomyTerm;
use Term\Entity\Term;
use Doctrine\Common\Collections\ArrayCollection;

class TermTaxonomyTest extends Model
{

    public function setUp()
    {
        $this->setObject(new TaxonomyTerm());
    }
    
    /*
     * (non-PHPdoc) @see \AtheneTest\TestCase\Model::getData()
     */
    protected function getData()
    {
        return array(
            'term' => $this->getMock('Term\Entity\Term'),
            'taxonomy' => $this->getMock('Taxonomy\Entity\Taxonomy'),
            'weight' => 1234,
            'description' => 'asdf',
            'parent' => 1234,
            'children' => new ArrayCollection(),
        );
    }

    public function testDelegation()
    {
        $this->inject();
        $this->getObject()
            ->getTerm()
            ->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('foo'));
        $this->getObject()
            ->getTerm()
            ->expects($this->once())
            ->method('getSlug')
            ->will($this->returnValue('foo-slug'));
        $this->assertEquals('foo', $this->getObject()
            ->getName());
        $this->assertEquals('foo-slug', $this->getObject()
            ->getSlug());
    }
    
    public function testGetAssociated(){
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->getObject()->getAssociated('entities'));
    }
    
    public function testCountAssociated(){
        $this->assertEquals(0,$this->getObject()->countAssociated('entities'));
    }
    
    public function testRest(){
    }
}