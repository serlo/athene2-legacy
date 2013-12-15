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
namespace Contexter\Manager;

use Contexter\Router;

interface ContextManagerInterface extends Router\RouterAwareInterface
{

    /**
     *
     * @param int $id            
     * @return Model\ContextModelInterface
     */
    public function getContext($id);

    /**
     *
     * @param int $id            
     * @return RouteInterface
     */
    public function getRoute($id);

    /**
     *
     * @param int $objectId            
     * @param string $type            
     * @param string $title            
     * @return Model\ContextModelInterface
     */
    public function add($objectId, $type, $title);

    /**
     *
     * @return Model\ContextModelInterface[]
     */
    public function findAll();

    /**
     *
     * @return array
     */
    public function findAllTypeNames();

    /**
     *
     * @param int $id            
     * @return self
     */
    public function removeRoute($id);

    /**
     *
     * @param int $id            
     * @return self
     */
    public function removeContext($id);
    
    /**
     * Make changes persistent
     * 
     * @return self
     */
    public function flush();
}