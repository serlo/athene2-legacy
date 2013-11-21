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
namespace Contexter\Entity;

use Uuid\Entity\UuidInterface;
use Doctrine\Common\Collections\Collection;

interface ContextInterface
{

    /**
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @return UuidInterface
     */
    public function getObject();

    /**
     *
     * @return string
     */
    public function getTitle();

    /**
     *
     * @return TypeInterface
     */
    public function getType();

    /**
     *
     * @return Collection
     */
    public function getRoutes();

    /**
     *
     * @param UuidInterface $uuid            
     * @return $this
     */
    public function setObject(UuidInterface $uuid);

    /**
     *
     * @param string $title            
     * @return $this
     */
    public function setTitle($title);

    /**
     *
     * @param TypeInterface $type            
     * @return $this
     */
    public function setType(TypeInterface $type);

    /**
     *
     * @param RouteInterface $route            
     * @return $this
     */
    public function addRoute(RouteInterface $route);
}