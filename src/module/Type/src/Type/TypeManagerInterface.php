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
namespace Type;

use Doctrine\Common\Collections\Collection;

interface TypeManagerInterface
{

    /**
     * Gets a type
     *
     * @param id $id            
     * @return Entity\TypeInterface
     */
    public function getType($id);

    /**
     * Gets a type by its name
     *
     * @param string $name            
     * @return Entity\TypeInterface
     */
    public function findTypeByName($name);

    /**
     * Gets a type by its name
     *
     * @return Ambigous<Collection, Entity\TypeInterface[]>
     */
    public function findAllTypes();
}