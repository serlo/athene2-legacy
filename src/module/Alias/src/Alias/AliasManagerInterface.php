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
     * @param                   $name
     * @param                   $source
     * @param UuidHolder        $object
     * @param InstanceInterface $instance
     * @return void
     */
    public function autoAlias($name, $source, UuidHolder $object, InstanceInterface $instance);

    /**
     * @param                   $source
     * @param                   $alias
     * @param                   $aliasFallback
     * @param UuidInterface     $uuid
     * @param InstanceInterface $instance
     * @return Entity\AliasInterface
     */
    public function createAlias($source, $alias, $aliasFallback, UuidInterface $uuid, InstanceInterface $instance);

    /**
     * @param UuidInterface $uuid
     * @return Entity\AliasInterface
     */
    public function findAliasByObject(UuidInterface $uuid);

    /**
     * @param string            $source
     * @param InstanceInterface $instance
     * @return string
     */
    public function findAliasBySource($source, InstanceInterface $instance);

    /**
     * @param                   $alias
     * @param InstanceInterface $instance
     * @return mixed
     */
    public function findCanonicalAlias($alias, InstanceInterface $instance);

    /**
     * @param string            $alias
     * @param InstanceInterface $instance
     * @return string
     */
    public function findSourceByAlias($alias, InstanceInterface $instance);
}