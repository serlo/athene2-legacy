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
namespace Alias;

use Instance\Entity\InstanceInterface;
use Uuid\Entity\UuidHolder;
use Uuid\Entity\UuidInterface;

interface AliasManagerInterface
{
    /**
     * @param string            $source
     * @param InstanceInterface $instance
     * @return string
     */
    public function findAliasBySource($source, InstanceInterface $instance);

    /**
     * @param string            $alias
     * @param InstanceInterface $instance
     * @return string
     */
    public function findSourceByAlias($alias, InstanceInterface $instance);

    /**
     * @param string            $source
     * @param string            $alias
     * @param InstanceInterface $instance
     * @param UuidInterface     $uuid
     * @return Entity\AliasInterface
     */
    public function createAlias($source, $alias, $aliasFallback, UuidInterface $uuid, InstanceInterface $instance);
    
    
    /**

     * @param string $name
     * @param string $source
     * @param UuidHolder $object
     * @param string            $name
     * @param string            $source
     * @param UuidInterface     $object
     * @param InstanceInterface $instance
     * @return self
     */
    public function autoAlias($name, $source, UuidHolder $object, InstanceInterface $instance);

    /**
     *
     * @param UuidInterface $uuid
     * @return Entity\AliasInterface
     */
    public function findAliasByObject(UuidInterface $uuid);
}