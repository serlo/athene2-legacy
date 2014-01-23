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
namespace Migrator;

use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\ServiceManager\ServiceLocatorInterface;

class Migrator
{
    protected $workers = [
        //'Migrator\Worker\ArticleWorker',
        'Migrator\Worker\FolderWorker',
        //'Migrator\Worker\UserWorker'
    ];

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var Filesystem
     */
    protected $cache;

    public function __construct(ServiceLocatorInterface $serviceLocator, Filesystem $cache)
    {
        $this->serviceLocator = $serviceLocator;
        $this->cache          = $cache;
    }

    public function migrate()
    {
        set_time_limit(0);
        $result = $this->serviceLocator->get('Migrator\Result');

        foreach ($this->workers as $worker) {
            $worker  = $this->serviceLocator->get($worker);
            $results = $worker->migrate($results);
        }

        //$this->cache->addItems($result->getMap());
    }
}
