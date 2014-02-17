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

use Doctrine\Common\Collections\ArrayCollection;
use Instance\Entity\Instance;
use Page\Entity\PageRepository;
use Page\Entity\PageRevision;
use User\Entity\Role;


/**
 * @codeCoverageIgnore
 */
class PageRepositoryTest extends Model
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
        $this->setObject(new PageRepository());
    }

    protected function getData()
    {
        $roles=new ArrayCollection();
        $roles->add(new Role());
        return array(
            'instance' => new Instance(),
            'current_revision' => new PageRevision(),
            'roles' => $roles
        );
    }

    public function testRoles()
    {
        $role = $this->getMock('User\Entity\Role');
        
        $this->getObject()->setRole($role);
        
        $this->assertSame($role, $this->getObject()
            ->getRoles()
            ->first());
        
        $this->assertTrue($this->getObject()
            ->hasRole($role));
        
        $this->getObject()->setRoles(new ArrayCollection());
        $this->assertEquals(0, $this->getObject()
            ->getRoles()
            ->count());
    }
    
    public function testRevisions() {
        
        $revision = $this->getMock('Page\Entity\PageRevision');
        $this->getObject()->addRevision($revision);
        
        $this->assertSame($revision, $this->getObject()
            ->getRevisions()
            ->first());
        
        $this->getObject()->setCurrentRevision($revision);

        $this->assertTrue($this->getObject()->hasCurrentRevision());

        $this->assertEquals($revision, $this->getObject()->getCurrentRevision());
        
        $this->getObject()->removeRevision($revision);
        
        $this->assertFalse($this->getObject()->hasCurrentRevision());
        
        $this->assertNull($this->getObject()->getCurrentRevision());
        
        
        
    }
    
    
    public function testPopulate()
    {
        $revision = new PageRevision();
        $this->getObject()->populate(array(
            'instance' => new Instance(),
            'current_revision' => $revision
        ));
        $this->assertEquals($revision, $this->getObject()->getCurrentRevision()
            );
        
    }
}