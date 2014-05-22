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
use Taxonomy\Manager\TaxonomyManagerAwareTrait;

class TaxonomyTermAdapter extends AbstractSphinxAdapter
{
    use TaxonomyManagerAwareTrait, NormalizerAwareTrait;

    protected $types = [
        'topic',
        'topic-folder',
        'topic-final-folder'
    ];

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
        $spinxQuery->select('name', 'id', 'type')->from('taxonomyTermIndex')->match(
                'name',
                $spinxQuery->escapeMatch($query) . '*'
            );

        /**
         * TODO use 64bit PHP (which isn't supported on windows)
         * PHP is a bitch and doesn't support bigint/uint
         * ->where('type_filter', '=', crc32($type));
         */

        $results = $spinxQuery->execute();

        foreach ($results as $result) {
            if ($result['type'] == $type) {
                $term = $this->getTaxonomyManager()->getTerm($result['id']);
                $resultInstance = new Result\Result();
                $resultInstance->setName($result['name']);
                $resultInstance->setId($result['id']);
                $resultInstance->setObject($term);
                $normalized = $this->getNormalizer()->normalize($term);
                $resultInstance->setRouteName($normalized->getRouteName());
                $resultInstance->setRouteParams($normalized->getRouteParams());
                $container->addResult($resultInstance);
            }
        }

        return $container;
    }
}
