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
namespace AtheneTest\TestCase;

use Doctrine\ORM\EntityRepository;
use AtheneTest\Bootstrap;
use Doctrine\Common\Persistence\ObjectRepository;

class ObjectManagerTestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp(){
        $em = self::createEntityManagerMock();
        $sm = Bootstrap::getServiceManager();
        $sm->setService('doctrine.entitymanager.orm_default', $em);
    }
    
    private $emMock;

    public function createEntityManagerMock ()
    {
        $emMock = $this->getMock('\Doctrine\ORM\EntityManager', array(
            'getRepository',
            'getClassMetadata',
            'persist',
            'flush',
            'find'
        ), array(), '', false);
        
        $emMock->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue((object) array(
            'name' => 'aClass'
        )));
        $emMock->expects($this->any())
            ->method('persist')
            ->will($this->returnValue(null));
        $emMock->expects($this->any())
            ->method('flush')
            ->will($this->returnValue(null));
        $this->emMock = $emMock;
        return $emMock;
    }
    
    public function injectEntityRepository( ObjectRepository $repository){
        $this->emMock->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($repository));        
    }
    
    public function createEntityRepositoryMock(array $methods = NULL){
        if(!$methods) {
            $methods = array(
                'find', 'findBy', 'clear', 'findOneBy', 'findAll'
            );
        }
        
        $repository = $this->getMock('\Doctrine\ORM\EntityRepository', $methods, array(), '', false);
        
        return $repository;
    }
}