<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Jakob Pfab (jakob.pfab@serlo.org)
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace PageTest\Entity;

use AtheneTest\TestCase\Model;
use Uuid\Entity\Uuid;
use Page\Entity\PageRepository;
use Page\Entity\PageRevision;
use Language\Entity\Language;
use User\Entity\User;


/**
 * @codeCoverageIgnore
 */
class PageRevisionTest extends Model
{

    
    /**
     * (non-PHPdoc)
     *
     * @see \AtheneTest\TestCase\Model::getObject()
     * @return User
     */
    public function getObject()
    {
        return parent::getObject();
    }

    public function setUp()
    {
        $this->setObject(new PageRevision());
    }

    protected function getData()
    {
        
        return array(
            'author' => new User(),
            'title' => 'title',
            'content' => 'content',
            'date' => new \DateTime(),
            'repository' => new PageRepository()
        );
    }

    
    public function testTrash(){
        $this->getObject()->trash();
        $this->assertTrue($this->getObject()->isTrashed());
        $repository = $this->getMock('Page\Entity\PageRepository');
        $this->getObject()->setRepository($repository);
        $this->assertEquals($repository,$this->getObject()->getRepository());
        $this->getObject()->delete();
    }
    
    public function testPopulate()
    {
        $this->getObject()->populate(array(
            'author' => new User(),
            'title' => 'title',
            'content' => 'content',
            'date' => new \DateTime(),
        ));
        $this->assertEquals('title', $this->getObject()->getTitle()
            );
        
    }
}