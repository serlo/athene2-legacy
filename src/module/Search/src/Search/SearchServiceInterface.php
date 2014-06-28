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

use Common\ObjectManager\Flushable;
use Zend\Paginator\Paginator;

interface SearchServiceInterface extends Flushable
{
    /**
     * @param int      $id
     * @param string   $title
     * @param string   $content
     * @param string   $type
     * @param string   $link
     * @param array    $keywords
     * @param int|null $instance
     * @return void
     * @throws \Exception
     */
    public function add($id, $title, $content, $type, $link, array $keywords, $instance = null);

    /**
     * Deletes an object by it's id
     *
     * @param int $id
     * @return void
     */
    public function delete($id);

    /**
     * Deletes all entries from the index
     *
     * @return void
     */
    public function erase();

    /**
     * Rebuilds the index
     *
     * @return void
     */
    public function rebuild();

    /**
     * @param string $query
     * @param int    $page
     * @param int    $itemsPerPage
     * @return Paginator
     */
    public function search($query, $page = 0, $itemsPerPage = 20);
}