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
use Common\Filter\Slugify;
use Common\Traits;
use Instance\Entity\InstanceInterface;
use Token\TokenizerAwareTrait;
use Uuid\Entity\UuidInterface;
use Uuid\Manager\UuidManagerAwareTrait;

class AliasManager implements AliasManagerInterface
{
    use Traits\ObjectManagerAwareTrait, ClassResolverAwareTrait;
    use TokenizerAwareTrait, UuidManagerAwareTrait;
    use Traits\RouterAwareTrait;

    /**
     * @var ManagerOptions
     */
    protected $options;

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
        $entity = $this->getAliasRepository()->findOneBy(
            [
                'alias'    => $alias,
                'instance' => $instance->getId()
            ]
        );

        if(!is_object($entity)){
            throw new Exception\CanonicalUrlNotFoundException(sprintf('No canonical url found'));
        }

        $canonical = $this->findAliasByObject($entity->getObject());

        if ($canonical !== $entity) {
            $router = $this->getRouter();
            $path = array_flip(explode('/', $canonical->getAlias()));
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

        /* @var $entity Entity\AliasInterface */
        $entity = $this->getAliasRepository()->findOneBy(
            [
                'alias'    => $alias,
                'instance' => $instance->getId()
            ]
        );

        if (!is_object($entity)) {
            throw new Exception\AliasNotFoundException(sprintf('Alias `%s` not found.', $alias));
        }

        return $entity->getSource();
    }

    public function findAliasBySource($source, InstanceInterface $instance)
    {
        if (!is_string($source)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected string but got %s',
                gettype($source)
            ));
        }

        $entity = $this->getAliasRepository()->findOneBy(
            [
                'source'   => $source,
                'instance' => $instance->getId()
            ],
            [
                'id' => 'desc'
            ]
        );

        if (!is_object($entity)) {
            throw new Exception\AliasNotFoundException(sprintf('Alias `%s` not found.', $source));
        }

        return $entity->getAlias();
    }

    public function createAlias($source, $alias, $aliasFallback, UuidInterface $uuid, InstanceInterface $instance)
    {
        if (!is_string($alias)) {
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($alias)));
        }

        if (!is_string($source)) {
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($source)));
        }

        if ($alias == $source) {
            throw new Exception\RuntimeException(sprintf(
                'Alias and source should not be equal: %s, %s',
                $alias,
                $source
            ));
        }

        $alias = $this->slugify($alias);

        try {
            $this->findSourceByAlias($alias, $instance);
            $alias = $alias . ' ' . uniqid();
            $alias = $this->slugify($alias);
        } catch (Exception\AliasNotFoundException $e) {
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
        $entity = $this->getAliasRepository()->findOneBy(
            [
                'uuid' => $uuid->getId()
            ],
            ['id' => 'desc']
        );

        if (!is_object($entity)) {
            throw new Exception\AliasNotFoundException();
        }

        return $entity;
    }

    public function flush($object = null)
    {
        $this->getObjectManager()->flush($object);
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
        $filter = new Slugify();
        $slugified = array();

        foreach (explode('/', $text) as $token) {
            $slugified[] = $filter->filter($token);
        }
        $text = implode('/', $slugified);

        return $text;
    }
}