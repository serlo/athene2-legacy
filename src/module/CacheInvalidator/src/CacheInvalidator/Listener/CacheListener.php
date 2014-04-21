<?php
namespace CacheInvalidator\Listener;

use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\Event;
use CacheInvalidator\Options\CacheOptions;
use Common\Listener\AbstractSharedListenerAggregate;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Cache\Storage\StorageInterface;

class CacheListener extends AbstractSharedListenerAggregate
{

    protected $cacheOptions, $serviceLocator;

    public function __construct(CacheOptions $cacheOptions, ServiceLocatorInterface $serviceLocator)
    {
        $this->cacheOptions = $cacheOptions;
        $this->serviceLocator = $serviceLocator;
    }

    public function attachShared(SharedEventManagerInterface $events)
    {
        $classes = $this->cacheOptions->getListens();
        
        
        foreach ($classes as $class => $options) {
            foreach ($options as $event => $storages) {
                $serviceLocator = $this->serviceLocator;
                $events->attach($class, $event, function(Event $e) use($class, $storages, $serviceLocator){
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
                });
            }
        }
    }
    
    protected function getMonitoredClass()
    {
        // TODO Auto-generated method stub
    }
}

 
