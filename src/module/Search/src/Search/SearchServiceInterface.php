<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Search;

interface SearchServiceInterface
{
    /**
     * Search for something.
     * <code>
     * $adapters = array(
     *     'AdapterA',
     *     'AdapterB',
     *     'AdapterC'
     * );
     * $results = $searchService->search('test', $adapters);
     * var_dump($results);
     * </code>
     *
     * @param string $query
     * @param array  $adapters
     * @return Result\Con
     */
    public function search($query, array $adapters);

    /**
     * @param Result\ContainerInterface $container
     * @return array
     */
    public function ajaxify(Result\ContainerInterface $container);
}
