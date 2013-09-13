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
namespace Uuid\Manager;

use Uuid\Entity\UuidHolder;
use Uuid\Entity\UuidInterface;
interface UuidManagerInterface
{

    /**
     * Gets/finds a Uuid.
     * 
     *  $um->get('1');
     *  $um->get('someH4ash');
     *  $um->get($uuidEntity);
     * 
     * @param int|string|UuidInterface $key
     * @return UuidInterface $uuid
     */
    public function get($key);
    
    /**
     * Creates an UuidEntity
     * 
     *  $uuid = $um->create();
     *  $um->inject($entity, $uuid);
     * 
     * @return UuidInterface $uuid
     */
    public function create();
    
    /**
     * Injects a UuidEntity
     * 
     *  $um->inject($entity); // Creates a new UuidEntity and injects it into $entity
     *  $um->inject($entity, $um->get('1')); // Injects the UuidEntity with the ID 1 into $entity
     * 
     * @param UuidHolder $entity
     * @param UuidInterface $uuid
     * @return UuidHolder $entity
     */
    public function inject(UuidHolder $entity, UuidInterface $uuid = NULL);
}