<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Search\Adapter\SphinxQL;

use Normalizer\NormalizerAwareTrait;
use Search\Result;

class EntityAdapter extends AbstractSphinxAdapter
{
    use \Entity\Manager\EntityManagerAwareTrait, NormalizerAwareTrait;

    protected $types = array('article', 'video', 'module');
    
    public function search($query, Result\Container $container)
    {
        foreach($this->types as $type){
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
        $spinxQuery->select('value', 'eid')
            ->from('entityIndex')
            ->match('value', $query . '*')
            ->match('type', $type);
        $results = $spinxQuery->execute();
        
        foreach($results as $result){
            $entity = $this->getEntityManager()->getEntity($result['eid']);
            $result = new Result\Result();
            $result->setName($this->getNormalizer()->normalize($entity)->getTitle());
            $result->setId($entity->getId());
            $result->setObject($entity);
            $result->setRouteName($this->getNormalizer()->normalize($entity)->getRouteName());
            $result->setRouteParams($this->getNormalizer()->normalize($entity)->getRouteParams());
            $container->addResult($result);
        }
        
        return $container;
    }
}