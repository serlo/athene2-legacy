<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Instance\Manager;

use Doctrine\Common\Collections\Collection;
use Instance\Entity\InstanceInterface;

interface InstanceManagerInterface
{

    /**
     * @return Collection InstanceInterface[]
     */
    public function findAllInstances();

    /**
     * @param string $name
     * @return InstanceInterface
     */
    public function findInstanceByName($name);

    /**
     * @return InstanceInterface
     */
    public function getDefaultInstance();

    /**
     * @param int $id
     * @return InstanceInterface
     */
    public function getInstance($id);

    /**
     * @return InstanceInterface
     */
    public function getInstanceFromRequest();

    /**
     * @param int $id
     * @return void
     */
    public function switchInstance($id);
}
