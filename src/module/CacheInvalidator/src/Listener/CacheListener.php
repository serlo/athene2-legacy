<?php
namespace CacheInvalidator\Listener;

use CacheInvalidator\Listener\AbstractListener;
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
            foreach ($options as $event => $storage) {
                
                /* @var $storage StorageInterface */
                $cache = $this->serviceLocator->get($storage);
                
                $events->attach($class, $event, function (Event $e) use($class, $options, $cache)
                {
                    
                    if (is_array($storage)) {
                        foreach ($storage as $key) {
                            $cache->removeItem($key);
                        }
                    } else {
                        $cache->flush();
                    }
                }, 1);
            }
        }
    }
    
    /*
     * (non-PHPdoc) @see \Common\Listener\AbstractSharedListenerAggregate::getMonitoredClass()
     */
    protected function getMonitoredClass()
    {
        // TODO Auto-generated method stub
    }
}

 
