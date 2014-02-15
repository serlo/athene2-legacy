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
        'Migrator\Worker\UserWorker',
        'Migrator\Worker\BlogWorker',
        'Migrator\Worker\FolderWorker',
        'Migrator\Worker\ArticleWorker',
        'Migrator\Worker\ExerciseWorker',
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
        $results  = [];
        $workload = [];

        $time_start = microtime(true);

        $i = 0;
        foreach ($this->workers as $worker) {
            $i++;
            $worker = $this->serviceLocator->get($worker);
            $worker->migrate($results, $workload);

            $idResults = [];
            foreach($results as $type => $result){
                foreach($result as $key => $result){
                    $idResults[$type][$key] = $result->getId();
                }
            }

            file_put_contents ("/var/www/src/data/migration-$i.dat", print_r($idResults, true));
        }

        $worker = $this->serviceLocator->get('Migrator\Worker\PostWorker');
        $worker->migrate($workload, $results);

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        echo "Migration took $time seconds";
    }
}
