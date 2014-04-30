<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Type\Entity;

interface TypeAwareInterface
{

    /**
     * Gets the type
     *
     * @return TypeInterface
     */
    public function getType();

    /**
     * Sets the type
     *
     * @param TypeInterface $type
     * @return self
     */
    public function setType(TypeInterface $type);
}
