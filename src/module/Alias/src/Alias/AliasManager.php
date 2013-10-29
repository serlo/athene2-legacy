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
use Uuid\Entity\UuidHolder;

class AliasManager implements AliasManagerInterface
{
    use Traits\ObjectManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait,\Common\Traits\ConfigAwareTrait,\Token\TokenizerAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'aliases' => array()
        );
    }

    public function autoAlias($name, $source, UuidHolder $object,\Language\Service\LanguageServiceInterface $language)
    {
        if (! is_string($name) || ! is_string($source))
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got `%s`', gettype($name)));
        
        if (! array_key_exists($name, $this->getOption('aliases')))
            throw new Exception\RuntimeException(sprintf('No configuration found for `%s`', $name));
        
        $options = $this->getOption('aliases')[$name];
        $provider = $options['provider'];
        $tokenString = $options['tokenize'];
        $fallbackString = $options['fallback'];
        
        $alias = $this->getTokenizer()->transliterate($provider, $object, $tokenString);
        $aliasFallback = $this->getTokenizer()->transliterate($provider, $object, $fallbackString);
        
        return $this->createAlias($source, $alias, $aliasFallback, $object, $language);
    }
    
    /*
     * (non-PHPdoc) @see \Alias\AliasManagerInterface::findSourceByAlias()
     */
    public function findSourceByAlias($alias,\Language\Service\LanguageServiceInterface $language)
    {
        if (! is_string($alias))
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($alias)));
            
            /* @var $entity Entity\AliasInterface */
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Alias\Entity\AliasInterface'))
            ->findOneBy(array(
            'alias' => $alias,
            'language' => $language->getId()
        ));
        
        if (! is_object($entity))
            throw new Exception\AliasNotFoundException(sprintf('Alias `%s` not found.', $alias));
        
        return $entity->getSource();
    }
    
    /*
     * (non-PHPdoc) @see \Alias\AliasManagerInterface::findAliasBySource()
     */
    public function findAliasEntityBySource($source,\Language\Service\LanguageServiceInterface $language)
    {
        if (! is_string($source))
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($source)));
        
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Alias\Entity\AliasInterface'))
            ->findOneBy(array(
            'source' => $source,
            'language' => $language->getId()
        ));
        
        if (! is_object($entity))
            return false;
        
        return $entity;
    }
    
    /*
     * (non-PHPdoc) @see \Alias\AliasManagerInterface::findAliasBySource()
     */
    public function findAliasBySource($source,\Language\Service\LanguageServiceInterface $language)
    {
        if (! is_string($source))
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($source)));
        
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Alias\Entity\AliasInterface'))
            ->findOneBy(array(
            'source' => $source,
            'language' => $language->getId()
        ));
        
        if (! is_object($entity))
            return false;
        
        return $entity->getAlias();
    }

    public function findAliasByUuid(UuidInterface $uuid)
    {
        
        /* @var $entity Entity\AliasInterface */
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Alias\Entity\AliasInterface'))
            ->findOneBy(array(
            'uuid' => $uuid->getId()
        ));
        
        if (! is_object($entity))
            throw new Exception\AliasNotFoundException(sprintf('Alias not found by uuid.', $uuid->getId()));
        
        return $entity;
    }
    
    /*
     * (non-PHPdoc) @see \Alias\AliasManagerInterface::createAlias()
     */
    public function createAlias($source, $alias, $aliasFallback, UuidHolder $uuid,\Language\Service\LanguageServiceInterface $language)
    {
        if (! is_string($alias))
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($alias)));
        
        if (! is_string($source))
            throw new Exception\InvalidArgumentException(sprintf('Expected string but got %s', gettype($source)));
        
        $filter = new Slugify();
        
        $slugified = array();
        foreach (explode('/', $alias) as $token) {
            $slugified[] = $filter->filter($token);
        }
        $alias = implode('/', $slugified);
        
        try {
            $this->findAliasByUuid($uuid->getUuidEntity());
            return $this;
            
            try {
                $source = $this->findAliasEntityBySource($alias, $language);
            } catch (Exception\AliasNotFoundException $e) {
                $alias = $aliasFallback;
            }
        } catch (Exception\AliasNotFoundException $e) {}
        
        $class = $this->getClassResolver()->resolveClassName('Alias\Entity\AliasInterface');
        $class = new $class();
        /* @var $class Entity\AliasInterface */
        $class->setSource($source);
        $class->setLanguage($language->getEntity());
        $class->setAlias($alias);
        $class->setUuid($uuid->getUuidEntity());
        $this->getObjectManager()->persist($class);
        
        return $this;
    }
}