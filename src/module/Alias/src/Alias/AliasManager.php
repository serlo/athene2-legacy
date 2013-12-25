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
namespace Alias;

use Common\Traits;
use Alias\Exception;
use Uuid\Entity\UuidInterface;
use Common\Filter\Slugify;
use Language\Entity\LanguageInterface;
use Alias\Options\ManagerOptions;

class AliasManager implements AliasManagerInterface
{
    use Traits\ObjectManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait,\Token\TokenizerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait;

    /**
     *
     * @var ManagerOptions
     */
    protected $options;

    /**
     *
     * @return ManagerOptions $options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     *
     * @param ManagerOptions $options            
     * @return self
     */
    public function setOptions(ManagerOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    public function autoAlias($name, $source, UuidInterface $object, LanguageInterface $language)
    {
        if (! is_string($name) || ! is_string($source)) {
            throw new Exception\InvalidArgumentException(sprintf('Expected name and source to be string but got "%s" and "%s"', gettype($name), gettype($source)));
        }
        
        if (! array_key_exists($name, $this->getOptions()->getAliases())) {
            throw new Exception\RuntimeException(sprintf('No configuration found for "%s"', $name));
        }
        
        $options = $this->getOptions()->getAliases()[$name];
        $provider = $options['provider'];
        $tokenString = $options['tokenize'];
        $fallbackString = $options['fallback'];
        $service = $this->getUuidManager()->createService($object);
        
        $alias = $this->getTokenizer()->transliterate($provider, $service, $tokenString);
        $aliasFallback = $this->getTokenizer()->transliterate($provider, $service, $fallbackString);
        
        return $this->createAlias($source, $alias, $aliasFallback, $object, $language);
    }

    public function findSourceByAlias($alias, LanguageInterface $language)
    {
        if (! is_string($alias)) {
            throw new Exception\InvalidArgumentException(sprintf('Expected alias to be string but got "%s"', gettype($alias)));
        }
        
        /* @var $entity Entity\AliasInterface */
        $entity = $this->getAliasRepository()->findOneBy([
            'alias' => $alias,
            'language' => $language->getId()
        ]);
        
        if (! is_object($entity)) {
            throw new Exception\AliasNotFoundException(sprintf('Alias `%s` not found.', $alias));
        }
        
        return $entity->getSource();
    }

    public function findAliasBySource($source, LanguageInterface $language)
    {
        if (! is_string($source))
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($source)));
        
        $entity = $this->getAliasRepository()->findOneBy([
            'source' => $source,
            'language' => $language->getId()
        ]);
        
        if (! is_object($entity))
            return false;
        
        return $entity->getAlias();
    }

    public function createAlias($source, $alias, $aliasFallback, UuidInterface $uuid, LanguageInterface $language)
    {
        if (! is_string($alias)) {
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($alias)));
        }
        
        if (! is_string($source)) {
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($source)));
        }
        
        $filter = new Slugify();
        
        $slugified = array();
        foreach (explode('/', $alias) as $token) {
            $slugified[] = $filter->filter($token);
        }
        $alias = implode('/', $slugified);
        
        if (is_object($this->findAliasByObject($uuid))) {
            return $this;
        }
        
        /* @var $class Entity\AliasInterface */
        $class = $this->getClassResolver()->resolve('Alias\Entity\AliasInterface');
        
        $class->setSource($source);
        $class->setLanguage($language);
        $class->setAlias($alias);
        $class->setObject($uuid);
        
        $this->getObjectManager()->persist($class);
        
        return $this;
    }

    protected function findAliasByObject(UuidInterface $uuid)
    {
        /* @var $entity Entity\AliasInterface */
        $entity = $this->getAliasRepository()->findOneBy([
            'uuid' => $uuid->getId()
        ]);
        
        return $entity;
    }

    protected function getEntityClassName()
    {
        return $this->getClassResolver()->resolveClassName('Alias\Entity\AliasInterface');
    }

    protected function getAliasRepository()
    {
        return $this->getObjectManager()->getRepository($this->getEntityClassName());
    }
}