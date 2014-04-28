<?php
namespace CacheInvalidator\Listener;

use CacheInvalidator\Options\CacheOptions;
use Zend\Cache\Storage\StorageInterface;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CacheListener Implements SharedListenerAggregateInterface
{

    /**
     * @var CacheOptions
     */
    protected $cacheOptions;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param CacheOptions            $cacheOptions
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(CacheOptions $cacheOptions, ServiceLocatorInterface $serviceLocator)
    {
        $this->cacheOptions   = $cacheOptions;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @param SharedEventManagerInterface $events
     */
    public function attachShared(SharedEventManagerInterface $events)
    {
        $classes = $this->cacheOptions->getListens();
        foreach ($classes as $class => $options) {
            foreach ($options as $event => $storages) {
                $serviceLocator = $this->serviceLocator;
                $events->attach(
                    $class,
                    $event,
                    function (Event $e) use ($class, $storages, $serviceLocator) {
                        foreach ($storages as $storagekey => $storage) {
                            if (is_array($storage)) {
                                /* @var $cache StorageInterface */
                                $cache = $serviceLocator->get($storagekey);
                                foreach ($storage as $concreteStorage => $key) {
                                    $cache->removeItem($key);
                                }
                            } else {
                                /* @var $cache StorageInterface */
                                $cache = $serviceLocator->get($storage);
                                $cache->flush();
                            }
                        }
                    }
                );
            }
        }
    }

    /**
     * Detach all previously attached listeners
     *
     * @param SharedEventManagerInterface $events
     */
    public function detachShared(SharedEventManagerInterface $events)
    {
        // TODO: Implement detachShared() method.
    }
}
