<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace CacheInvalidator\Invalidator;

use Entity\Entity\EntityInterface;
use Page\Entity\PageRepositoryInterface;
use StrokerCache\Service\CacheService;
use Zend\Cache\Storage\FlushableInterface;
use Zend\Cache\Storage\StorageInterface;
use Zend\EventManager\Event;

class StrokerStorageInvalidator implements InvalidatorInterface
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var CacheService
     */
    protected $cacheService;


    /**
     * @param CacheService     $cacheService
     * @param StorageInterface $storage
     */
    public function __construct(CacheService $cacheService, StorageInterface $storage)
    {
        $this->storage      = $storage;
        $this->cacheService = $cacheService;
    }

    /**
     * @param Event  $e
     * @param string $class
     * @param string $event
     * @return void
     */
    public function invalidate(Event $e, $class, $event)
    {
        if ($class == 'Navigation\Manager\NavigationManager') {
            if ($this->storage instanceof FlushableInterface) {
                $this->storage->flush();
            }
        } elseif ($class == 'Taxonomy\Manager\TaxonomyManager') {
            if ($this->storage instanceof FlushableInterface) {
                $this->storage->flush();
            }
        } elseif ($class == 'Versioning\RepositoryManager') {
            $repository = $e->getParam('repository');
            $result     = false;
            if ($repository instanceof EntityInterface) {
                $result = $this->cacheService->clearByTags(['route_entity/page', 'route_taxonomy/term/get']);
            } elseif ($repository instanceof PageRepositoryInterface) {
                $result = $this->cacheService->clearByTags(['route_page/view']);
            }
            if ($this->storage instanceof FlushableInterface && !$result) {
                $this->storage->flush();
            }
        }
    }
}
