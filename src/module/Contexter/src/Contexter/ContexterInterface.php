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
namespace Contexter;

use Uuid\Entity\UuidHolder;
use Contexter\Entity\TypeInterface;
use Doctrine\Common\Collections\Collection;
use Uuid\Entity\UuidInterface;
use Contexter\Entity\RouteInterface;

interface ContexterInterface extends Router\RouterAwareInterface
{

    /**
     *
     * @param int $id            
     * @return ContextInterface
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
     * @param UuidInterface $object            
     * @param string $type            
     * @param string $title            
     * @return ContextInterface
     */
    public function add(UuidInterface $object, $type, $title);

    /**
     *
     * @param string $type            
     * @return ContextInterface[]
     */
    public function findAllByType($name);

    /**
     *
     * @return ContextInterface[]
     */
    public function findAll();

    /**
     *
     * @return Collection|TypeInterface[]
     */
    public function findAllTypes();

    /**
     *
     * @return array
     */
    public function findAllTypeNames();
    
    /**
     * 
     * @param int $id
     * @return $this
     */
    public function removeRoute($id);
    
    /**
     * 
     * @param int $id
     * @return $this
     */
    public function removeContext($id);
}