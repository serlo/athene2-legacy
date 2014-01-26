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
namespace Migrator\Worker;

use Doctrine\Common\Persistence\ObjectManager;
use Migrator\Converter\PostConverter;

class PostWorker {
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct(ObjectManager $objectManager){
        $this->objectManager = $objectManager;
    }

    public function migrate(array $workload, array $map){
        $converter = new PostConverter();

        foreach($workload as $do){
            $entity = $do['entity'];
            foreach($do['work'] as $field){
                $value = $converter->convert($field['value'], $map);
                $persist = $entity->set($field['name'], $value);
            }
            $this->objectManager->persist($entity);
            $this->objectManager->persist($persist);
        }

        $this->objectManager->flush();
    }
}
