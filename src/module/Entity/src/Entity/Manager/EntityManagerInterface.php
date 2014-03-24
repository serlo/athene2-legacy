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
namespace Entity\Manager;

use Common\ObjectManager\Flushable;
use Entity\Entity\EntityInterface;
use Instance\Entity\InstanceInterface;

interface EntityManagerInterface extends Flushable
{

    /**
     * @param string            $type
     * @param array             $data
     * @param InstanceInterface $instance
     * @return EntityInterface
     */
    public function createEntity($type, array $data = [], InstanceInterface $instance);

    /**
     * @param int $id
     * @return EntityInterface
     */
    public function getEntity($id);
}