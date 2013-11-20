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
namespace Contexter;

use Contexter\Entity;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use ClassResolver\ClassResolverAwareInterface;

class Context implements ContextInterface, ObjectManagerAwareInterface, ClassResolverAwareInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\Common\Traits\ConfigAwareTrait,\Common\Traits\RouterAwareTrait,\ClassResolver\ClassResolverAwareTrait;

    /**
     *
     * @var Entity\ContextInterface
     */
    protected $entity;

    public function getDefaultConfig()
    {
        return array();
    }

    public function getUrl()
    {
        return $this->getRouter()->assemble(array(
            'uuid' => $this->getEntity()
                ->getObject()->getId()
        ), array(
            'name' => 'uuid/router'
        ));
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }
    

    public function getTitle()
    {
        return $this->getEntity()->getTitle();
    }
    
    public function getRoutes(){
        return $this->getEntity()->getRoutes();
    }

    public function setEntity(Entity\ContextInterface $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function addRoute($routeName, array $params = array())
    {
        /* @var $route Entity\RouteInterface */
        $route = $this->getClassResolver()->resolve('Contexter\Entity\RouteInterface');
        $route->setName($routeName);
        $route->addParameters($params);
        $route->setContext($this->getEntity());
        $this->getEntity()->addRoute($route);
        $this->getObjectManager()->persist($route);
        return $route;
    }
}