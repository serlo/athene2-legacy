<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Alias;

use Alias\Exception;
use Alias\Options\ManagerOptions;
use ClassResolver\ClassResolverAwareTrait;
use ClassResolver\ClassResolverInterface;
use Common\Filter\Slugify;
use Common\Traits;
use Doctrine\Common\Persistence\ObjectManager;
use Instance\Entity\InstanceInterface;
use Token\TokenizerAwareTrait;
use Token\TokenizerInterface;
use Uuid\Entity\UuidInterface;
use Uuid\Manager\UuidManagerAwareTrait;
use Zend\Cache\Storage\StorageInterface;
use Zend\Mvc\Router\RouteInterface;

class AliasManager implements AliasManagerInterface
{
    use Traits\ObjectManagerAwareTrait, ClassResolverAwareTrait;
    use TokenizerAwareTrait, Traits\RouterAwareTrait;

    /**
     * @var ManagerOptions
     */
    protected $options;

    /**
     * @var StorageInterface
     */
    protected $storage;

    public function __construct(
        ClassResolverInterface $classResolver,
        ManagerOptions $options,
        ObjectManager $objectManager,
        RouteInterface $router,
        StorageInterface $storage,
        TokenizerInterface $tokenizer
    ) {
        $this->classResolver = $classResolver;
        $this->tokenizer     = $tokenizer;
        $this->objectManager = $objectManager;
        $this->router        = $router;
        $this->storage       = $storage;
        $this->options       = $options;
    }

    public function autoAlias($name, $source, UuidInterface $object, InstanceInterface $instance)
    {
        if (!is_string($name) || !is_string($source)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected name and source to be string but got "%s" and "%s"',
                gettype($name),
                gettype($source)
            ));
        }

        if (!array_key_exists($name, $this->getOptions()->getAliases())) {
            throw new Exception\RuntimeException(sprintf('No configuration found for "%s"', $name));
        }

        $options        = $this->getOptions()->getAliases()[$name];
        $provider       = $options['provider'];
        $tokenString    = $options['tokenize'];
        $fallbackString = $options['fallback'];
        $alias          = $this->getTokenizer()->transliterate($provider, $object, $tokenString);
        $aliasFallback  = $this->getTokenizer()->transliterate($provider, $object, $fallbackString);

        return $this->createAlias($source, $alias, $aliasFallback, $object, $instance);
    }

    public function findCanonicalAlias($alias, InstanceInterface $instance)
    {
        /* @var $entity Entity\AliasInterface */
        $criteria = ['alias' => $alias, 'instance' => $instance->getId()];
        $entity   = $this->getAliasRepository()->findOneBy($criteria);

        if (!is_object($entity)) {
            throw new Exception\CanonicalUrlNotFoundException(sprintf('No canonical url found'));
        }

        $canonical = $this->findAliasByObject($entity->getObject());

        if ($canonical !== $entity) {
            $router = $this->getRouter();
            $path   = array_flip(explode('/', $canonical->getAlias()));
            $url    = $router->assemble($path, ['name' => 'alias/path']);
            if ($url !== $alias) {
                return $url;
            }
        }

        throw new Exception\CanonicalUrlNotFoundException(sprintf('No canonical url found'));
    }

    public function findSourceByAlias($alias, InstanceInterface $instance)
    {
        if (!is_string($alias)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected alias to be string but got "%s"',
                gettype($alias)
            ));
        }

        $key = 'source:by:alias:' . $instance->getId() . ':' . $alias;
        if ($this->storage->hasItem($key)) {
            // The item is null so it didn't get found.
            $item = $this->storage->getItem($key);
            if ($item === null) {
                throw new Exception\AliasNotFoundException(sprintf('Alias `%s` not found.', $alias));
            }
        }

        /* @var $entity Entity\AliasInterface */
        $criteria = ['alias' => $alias, 'instance' => $instance->getId()];
        $entity   = $this->getAliasRepository()->findOneBy($criteria);

        if (!is_object($entity)) {
            $this->storage->setItem($key, null);
            throw new Exception\AliasNotFoundException(sprintf('Alias `%s` not found.', $alias));
        }

        $source = $entity->getSource();
        $this->storage->setItem($key, $source);

        return $source;
    }

    public function findAliasBySource($source, InstanceInterface $instance)
    {
        if (!is_string($source)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected string but got %s',
                gettype($source)
            ));
        }

        $key = 'alias:by:source:' . $instance->getId() . ':' . $source;
        if ($this->storage->hasItem($key)) {
            $item = $this->storage->getItem($key);
            // The item is null so it didn't get found.
            if ($item === null) {
                throw new Exception\AliasNotFoundException(sprintf('Alias `%s` not found.', $source));
            }
        }

        $params = ['source' => $source, 'instance' => $instance->getId()];
        $order  = ['id' => 'desc'];
        $entity = $this->getAliasRepository()->findOneBy($params, $order);

        if (!is_object($entity)) {
            // Set it to null so we know that this doesn't exist
            $this->storage->setItem($key, null);
            throw new Exception\AliasNotFoundException(sprintf('Alias `%s` not found.', $source));
        }

        $alias = $entity->getAlias();
        $this->storage->setItem($key, $alias);

        return $alias;
    }

    public function createAlias($source, $alias, $aliasFallback, UuidInterface $uuid, InstanceInterface $instance)
    {
        if (!is_string($alias)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected parameter 2 to be string, but got %s',
                gettype($alias)
            ));
        }

        if (!is_string($source)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected parameter 1 to be string, but got %s',
                gettype($source)
            ));
        }

        if ($alias == $source) {
            throw new Exception\RuntimeException(sprintf(
                'Alias and source should not be equal: %s, %s',
                $alias,
                $source
            ));
        }

        $alias       = $this->slugify($alias);
        $useFallback = true;

        $aliases = $this->findAliases($uuid, $alias);
        foreach ($aliases as $entity) {
            if ($entity->getAlias() == $alias) {
                $useFallback = false;
                break;
            }
        }

        if ($useFallback) {
            $alias = $alias . ' ' . uniqid();
            $alias = $this->slugify($alias);
        }

        /* @var $class Entity\AliasInterface */
        $class = $this->getClassResolver()->resolve('Alias\Entity\AliasInterface');

        $class->setSource($source);
        $class->setInstance($instance);
        $class->setAlias($alias);
        $class->setObject($uuid);
        $this->getObjectManager()->persist($class);

        return $class;
    }

    public function findAliasByObject(UuidInterface $uuid)
    {
        /* @var $entity Entity\AliasInterface */
        $criteria = ['uuid' => $uuid->getId()];
        $order    = ['id' => 'desc'];
        $entity   = $this->getAliasRepository()->findOneBy($criteria, $order);

        if (!is_object($entity)) {
            throw new Exception\AliasNotFoundException();
        }

        return $entity;
    }

    public function flush($object = null)
    {
        $this->getObjectManager()->flush($object);
    }

    public function findAliases(UuidInterface $object, $alias)
    {
        $className = $this->getEntityClassName();
        $criteria  = ['uuid' => $object->getId(), 'alias' => $alias];
        $aliases   = $this->getObjectManager()->getRepository($className)->findBy($criteria);

        return $aliases;
    }

    /**
     * @return ManagerOptions $options
     */
    public function getOptions()
    {
        return $this->options;
    }

    protected function getAliasRepository()
    {
        return $this->getObjectManager()->getRepository($this->getEntityClassName());
    }

    protected function getEntityClassName()
    {
        return $this->getClassResolver()->resolveClassName('Alias\Entity\AliasInterface');
    }

    /**
     * @param ManagerOptions $options
     * @return void
     */
    public function setOptions(ManagerOptions $options)
    {
        $this->options = $options;
    }

    protected function slugify($text)
    {
        $filter    = new Slugify();
        $slugified = [];

        foreach (explode('/', $text) as $token) {
            $slugified[] = $filter->filter($token);
        }

        $text = implode('/', $slugified);

        return $text;
    }
}