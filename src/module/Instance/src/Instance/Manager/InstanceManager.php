<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Instance\Manager;

use ClassResolver\ClassResolverAwareTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Instance\Entity\InstanceInterface;
use Instance\Exception;
use Zend\Session\AbstractContainer;
use Zend\Session\Container;

class InstanceManager implements InstanceManagerInterface
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait;

    /**
     * @var InstanceInterface
     */
    protected $requestInstance;

    /**
     * @var AbstractContainer
     */
    protected $container;

    /**
     * @var int
     */
    private $defaultInstance = 1;

    public function findAllInstances()
    {
        $collection = $this->getObjectManager()->getRepository(
            $this->getClassResolver()->resolveClassName('Instance\Entity\InstanceInterface')
        )->findAll();

        return new ArrayCollection($collection);
    }

    public function getDefaultInstance()
    {
        return $this->getInstance($this->defaultInstance);
    }

    public function setDefaultInstance($id)
    {
        $this->defaultInstance = $id;
    }

    public function getInstanceFromRequest()
    {
        /*if (!array_key_exists('HTTP_HOST', (array)$_SERVER)) {
            $this->requestInstance = $this->getDefaultInstance();
        }

        if (!$this->requestInstance) {
            $subdomain = explode('.', $_SERVER['HTTP_HOST'])[0];

            try {
                $this->requestInstance = $this->findInstanceByName($subdomain);
            } catch (Exception\InstanceNotFoundException $e) {
                $this->requestInstance = $this->getDefaultInstance();
            }
        }

        return $this->requestInstance;*/

        if (!is_object($this->requestInstance)) {
            $container = $this->getContainer();
            if (!$container->offsetExists('instance')) {
                $this->requestInstance = $this->getDefaultInstance();
            } else {
                $this->requestInstance = $this->getInstance($container->offsetGet('instance'));
            }
        }

        return $this->requestInstance;
    }

    public function getInstance($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Instance\Entity\InstanceInterface');

        $instance = $this->getObjectManager()->find($className, $id);

        if (!is_object($instance)) {
            throw new Exception\InstanceNotFoundException(sprintf('Instance %s could not be found', $id));
        }

        return $instance;
    }

    public function findInstanceByName($name)
    {
        if (!is_string($name)) {
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($name)));
        }

        $className = $this->getClassResolver()->resolveClassName('Instance\Entity\InstanceInterface');
        $criteria  = ['name' => $name];
        $instance  = $this->getObjectManager()->getRepository($className)->findOneBy($criteria);

        if (!is_object($instance)) {
            throw new Exception\InstanceNotFoundException(sprintf('Instance %s could not be found', $name));
        }

        return $instance;
    }

    public function switchInstance($id)
    {
        $instance  = $this->getInstance($id);
        $container = $this->getContainer();
        $container->offsetSet('instance', $instance->getId());
    }

    /**
     * @return AbstractContainer
     */
    public function getContainer()
    {
        if (!is_object($this->container)) {
            $this->container = new Container('instance');
        }

        return $this->container;
    }
}
