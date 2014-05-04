<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Instance\Manager;

use Authorization\Service\AuthorizationAssertionTrait;
use ClassResolver\ClassResolverAwareTrait;
use ClassResolver\ClassResolverInterface;
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Instance\Entity\InstanceInterface;
use Instance\Exception;
use Instance\Options\InstanceOptions;
use Zend\Session\AbstractContainer;
use Zend\Session\Container;
use ZfcRbac\Service\AuthorizationService;
use ZfcRbac\Service\AuthorizationServiceAwareTrait;

class InstanceManager implements InstanceManagerInterface
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait;
    use AuthorizationAssertionTrait;

    /**
     * @var InstanceInterface
     */
    protected $requestInstance;

    /**
     * @var AbstractContainer
     */
    protected $container;

    /**
     * @var InstanceOptions
     */
    protected $options;

    /**
     * @param AuthorizationService   $authorizationService
     * @param ClassResolverInterface $classResolver
     * @param InstanceOptions        $options
     * @param ObjectManager          $objectManager
     */
    public function __construct(
        AuthorizationService $authorizationService,
        ClassResolverInterface $classResolver,
        InstanceOptions $options,
        ObjectManager $objectManager
    ) {
        $this->setAuthorizationService($authorizationService);
        $this->classResolver = $classResolver;
        $this->objectManager = $objectManager;
        $this->options       = $options;
    }

    public function findAllInstances()
    {
        $className  = $this->getClassResolver()->resolveClassName('Instance\Entity\InstanceInterface');
        $collection = $this->getObjectManager()->getRepository($className)->findAll();
        $return     = new ArrayCollection();

        foreach ($collection as $instance) {
            if ($this->getAuthorizationService()->isGranted('instance.get', $instance)) {
                $return->add($instance);
            }
        }

        return $return;
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

        $this->assertGranted('instance.get', $instance);

        return $instance;
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

    public function getDefaultInstance()
    {
        return $this->getInstance($this->options->getDefault());
    }

    public function getInstance($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Instance\Entity\InstanceInterface');
        $instance  = $this->getObjectManager()->find($className, $id);

        if (!is_object($instance)) {
            throw new Exception\InstanceNotFoundException(sprintf('Instance %s could not be found', $id));
        }
        $this->assertGranted('instance.get', $instance);

        return $instance;
    }

    public function getInstanceFromRequest()
    {
        if ($this->options->getUseCookie()) {
            return $this->getInstanceFromCookie();
        }
        return $this->getInstanceFromDomain();
    }

    public function switchInstance($id)
    {
        $instance  = $this->getInstance($id);
        $container = $this->getContainer();
        $container->offsetSet('instance', $instance->getId());
    }

    protected function getInstanceFromCookie()
    {

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

    protected function getInstanceFromDomain()
    {
        if (!array_key_exists('HTTP_HOST', (array)$_SERVER)) {
            throw new Exception\RuntimeException('No domain set.');
        }

        if (!$this->requestInstance) {
            $subDomain = explode('.', $_SERVER['HTTP_HOST'])[0];
            $this->requestInstance = $this->findInstanceByName($subDomain);
        }

        return $this->requestInstance;
    }
}
