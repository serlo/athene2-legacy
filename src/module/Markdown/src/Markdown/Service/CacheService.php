<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	    LGPL-3.0
 * @license	    http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright	Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Markdown\Service;

use Markdown\Entity\CacheableInterface;
use Markdown\Exception;

class CacheService implements CacheServiceInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Common\Traits\FlushableTrait,\ClassResolver\ClassResolverAwareTrait;

    /**
     *
     * @see CacheServiceInterface::getCache()
     */
    public function getCache(CacheableInterface $object, $field)
    {
        $id = $this->getGuid($object);
        $className = $this->getClassResolver()->resolveClassName('Markdown\Entity\CacheInterface');
        $cache = $this->getObjectManager()
            ->getRepository($className)
            ->findOneBy([
            'guid' => $id
        ]);
        
        if (! is_object($cache)) {
            throw new Exception\RuntimeException(sprintf('Could not find a cache by guid "%s"', $id));
        }
        
        return $cache;
    }

    /**
     *
     * @see CacheServiceInterface::setCache()
     */
    public function setCache(CacheableInterface $object, $field, $content)
    {
        /* @var $cache \Markdown\Entity\CacheInterface */
        try {
            $cache = $this->getCache($object);
        } catch (Exception\RuntimeException $e) {
            $id = $this->getGuid($object);
            $cache = $this->getClassResolver()->resolve('Markdown\Entity\CacheInterface', false);
            $cache->setGuid($id);
        }
        
        $cache->setContent($content);
        $this->getObjectManager()->persist($cache);
        
        return $this;
    }

    protected function getGuid(CacheableInterface $object, $field)
    {
        return get_class($object) . '::' . $object->getId() . '::' . $field;
    }
}