<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Search\Adapter\SphinxQL;

use Normalizer\NormalizerAwareTrait;
use Search\Result;

class EntityAdapter extends AbstractSphinxAdapter
{
    use \Entity\Manager\EntityManagerAwareTrait, NormalizerAwareTrait;

    protected $types = ['article', 'video', 'course'];

    public function search($query, Result\Container $container)
    {
        foreach ($this->types as $type) {
            $resultContainer = $this->searchTypes($query, $type);
            $container->addContainer($resultContainer);
        }

        return $container;
    }

    protected function searchTypes($query, $type)
    {
        $container = new Result\Container();
        $container->setName($type);

        $spinxQuery = $this->forge();
        $spinxQuery->select('eid')->from('entityIndex')->match('value', $query . '*')->match('type', $type);
        $results   = $spinxQuery->execute();
        $processed = [];

        foreach ($results as $result) {
            if (!in_array($result['eid'], $processed)) {
                $processed[] = $result['eid'];
                $entity      = $this->getEntityManager()->getEntity($result['eid']);
                $result      = new Result\Result();
                $normalized = $this->getNormalizer()->normalize($entity);
                $result->setName($normalized->getTitle());
                $result->setId($entity->getId());
                $result->setObject($entity);
                $result->setRouteName($normalized->getRouteName());
                $result->setRouteParams($normalized->getRouteParams());
                $container->addResult($result);
            }
        }

        return $container;
    }
}
