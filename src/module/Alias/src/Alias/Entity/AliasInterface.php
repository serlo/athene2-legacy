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
namespace Alias\Entity;

use Instance\Entity\InstanceAwareInterface;
use Uuid\Entity\UuidInterface;
use DateTime;

interface AliasInterface extends InstanceAwareInterface
{

    /**
     * Returns the ID
     *
     * @return int
     */
    public function getId();

    /**
     * Returns the source
     *
     * @return string
     */
    public function getSource();

    /**
     * Returns the alias
     *
     * @return string
     */
    public function getAlias();

    /**
     * Gets the object
     *
     * @return UuidInterface
     */
    public function getObject();

    /**
     * Sets the source
     *
     * @param string $source
     * @return void
     */
    public function setSource($source);

    /**
     * Sets the alias
     *
     * @param string $alias
     * @return void
     */
    public function setAlias($alias);

    /**
     * Sets the object
     *
     * @param UuidInterface $uuid
     * @return void
     */
    public function setObject(UuidInterface $uuid);

    /**
     * @return DateTime
     */
    public function getTimestamp();
}