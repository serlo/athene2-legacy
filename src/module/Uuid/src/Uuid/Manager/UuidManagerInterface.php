<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Uuid\Manager;

use Common\ObjectManager\Flushable;
use Uuid\Entity\UuidInterface;

interface UuidManagerInterface extends Flushable
{

    /**
     * Get an Uuid.
     * <code>
     * $um->getUuid('1');
     * $um->getUuid('someH4ash');
     * $um->getUuid($uuidEntity);
     * </code>
     *
     * @param int|string|UuidInterface $key
     * @return UuidInterface $uuid
     */
    public function getUuid($key);

    /**
     * @param int $id
     * @return void
     */
    public function trashUuid($id);

    /**
     * @param int $id
     * @return void
     */
    public function restoreUuid($id);

    /**
     * @param int $id
     * @return void
     */
    public function purgeUuid($id);

    /**
     * Finds an Uuid by its name
     * <code>
     * $um->findUuidByName('j49jfbaAK');
     * </code>
     *
     * @param unknown $string
     */
    public function findUuidByName($string);

    /**
     * Creates an UuidEntity
     * <code>
     * $uuid = $um->createUuid();
     * $um->injectUuid($entity, $uuid);
     * </code>
     *
     * @return UuidInterface $uuid
     */
    public function createUuid();

    /**
     * Finds Uuuids by their trashed attribute.
     * <code>
     * $uuids = $um->findByTrashed(true);
     * foreach($uuids as $uuid)
     * {
     * echo $uuid->getId();
     * }
     * </code>
     *
     * @param bool $trashed
     * @return UuidInterface[]
     */
    public function findByTrashed($trashed);
}
