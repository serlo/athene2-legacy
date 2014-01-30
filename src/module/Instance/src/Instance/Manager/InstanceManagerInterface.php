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
    public function findAllTenants();

    /**
     * @param string $name
     * @return InstanceInterface
     */
    public function findTenantByName($name);

    /**
     * @return InstanceInterface
     */
    public function getDefaultTenant();

    /**
     * @param int $id
     * @return InstanceInterface
     */
    public function getTenant($id);

    /**
     * @return InstanceInterface
     */
    public function getTenantFromRequest();
}
