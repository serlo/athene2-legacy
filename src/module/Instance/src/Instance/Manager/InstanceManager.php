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

class InstanceManager implements InstanceManagerInterface
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait;

    private $defaultInstance = 1;

    /**
     * @var InstanceInterface
     */
    protected $requestTenant;

    public function setDefaultInstance($id)
    {
        $this->defaultInstance = $id;

        return $this;
    }

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

    public function getInstanceFromRequest()
    {
        if (!array_key_exists('HTTP_HOST', (array)$_SERVER)) {
            $this->requestTenant = $this->getDefaultInstance();
        }

        if (!$this->requestTenant) {
            $subdomain = explode('.', $_SERVER['HTTP_HOST'])[0];

            try {
                $this->requestTenant = $this->findInstanceByName($subdomain);
            } catch (Exception\InstanceNotFoundException $e) {
                $this->requestTenant = $this->getDefaultInstance();
            }
        }

        return $this->requestTenant;
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

        $instance = $this->getObjectManager()->getRepository(
            $this->getClassResolver()->resolveClassName('Instance\Entity\InstanceInterface')
        )->findOneBy(
                array(
                    'name' => $name
                )
            );

        if (!is_object($instance)) {
            throw new Exception\InstanceNotFoundException(sprintf('Instance %s could not be found', $name));
        }

        return $instance;
    }
}
