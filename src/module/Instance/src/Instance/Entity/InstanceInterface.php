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
namespace Instance\Entity;

interface InstanceInterface
{

    /**
     * Gets the id
     *
     * @return int $id
     */
    public function getId();

    /**
     * Returns the code.
     * <code>
     * echo $instance->getCode(); // prints: 'de'
     * </code>
     *
     * @return string $code
     */
    public function getName();

    /**
     * Sets the code
     *
     * @param field_type $name
     * @return self
     */
    public function setName($name);
}
