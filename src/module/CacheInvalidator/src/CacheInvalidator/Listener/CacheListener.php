<?php
namespace CacheInvalidator\Listener;

use CacheInvalidator\Manager\InvalidatorManager;
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
     * @var InvalidatorManager
     */
    protected $strategyManager;

    /**
     * @param CacheOptions       $cacheOptions
     * @param InvalidatorManager $strategyManager
     */
    public function __construct(CacheOptions $cacheOptions, InvalidatorManager $strategyManager)
    {
        $this->cacheOptions   = $cacheOptions;
        //$this->serviceLocator = $serviceLocator;
        $this->strategyManager = $strategyManager;
    }

    /**
     * @param SharedEventManagerInterface $events
     */
    public function attachShared(SharedEventManagerInterface $events)
    {
        $classes = $this->cacheOptions->getListens();
        foreach ($classes as $class => $options) {
            foreach ($options as $event => $invalidators) {
                $strategyManager = $this->strategyManager;
                $events->attach(
                    $class,
                    $event,
                    function (Event $e) use ($class, $invalidators, $strategyManager) {
                        foreach ($invalidators as $invalidator) {
                            $invalidator = $strategyManager->get($invalidator);
                            $invalidator->invalidate($e);
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
