<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Contexter\Entity;

use Doctrine\Common\Collections\Collection;
use Instance\Entity\InstanceAwareInterface;
use Type\Entity\TypeAwareInterface;
use Uuid\Entity\UuidInterface;

interface ContextInterface extends TypeAwareInterface, InstanceAwareInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return UuidInterface
     */
    public function getObject();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return Collection
     */
    public function getRoutes();

    /**
     * @param UuidInterface $uuid
     * @return self
     */
    public function setObject(UuidInterface $uuid);

    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title);

    /**
     * @param RouteInterface $route
     * @return self
     */
    public function addRoute(RouteInterface $route);
}