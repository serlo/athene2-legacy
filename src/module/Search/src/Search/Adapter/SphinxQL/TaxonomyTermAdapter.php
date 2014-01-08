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

class TaxonomyTermAdapter extends AbstractSphinxAdapter
{
    use \Taxonomy\Manager\TaxonomyManagerAwareTrait, NormalizerAwareTrait;

    protected $types = array(
        'topic',
        'abstract-topic'
    );

    public function search($query)
    {
        $container = new Result\Container();
        $container->setName('taxonomy');
        
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
        $spinxQuery->select('name', 'id', 'type')
            ->from('taxonomyTermIndex')
            ->match('name', $query)
            ->match('type', $type);
        
        /**
         * TODO use 64bit PHP (which isn't supported on windows)
         * PHP is a bitch and doesn't support bigint/uint
         * ->where('type_filter', '=', crc32($type));
         */
        
        $results = $spinxQuery->execute();
        
        foreach ($results as $result) {
            $term = $this->getTaxonomyManager()->getTerm($result['id']);
            $resultInstance = new Result\Result();
            $resultInstance->setName($result['name']);
            $resultInstance->setId($result['id']);
            $resultInstance->setObject($term);
            $resultInstance->setRouteName($this->getNormalizer()->normalize($term)->getRouteName());
            $resultInstance->setRouteParams($this->getNormalizer()->normalize($term)->getRouteParams());
            $container->addResult($resultInstance);
        }
        
        return $container;
    }
}