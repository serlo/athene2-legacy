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
use Instance\Entity\InstanceInterface;
use Uuid\Entity\UuidInterface;
use Doctrine\Common\Collections\Collection;

interface UuidManagerInterface extends Flushable
{
    /**
     * Finds Uuuids by their trashed attribute.
     * <code>
     * $uuids = $um->findByTrashed(true);
     * foreach($uuids as $uuid)
     * {
     *    echo $uuid->getId();
     * }
     * </code>
     *
     * @param bool $trashed
     * @return UuidInterface[]
     */
    public function findByTrashed($trashed);

    /**
     * Finds all Uuids
     * <code>
     *    $collection = $um->findAll();
     * </code>
     *
     * @return UuidInterface[]|Collection
     */
    public function findAll();

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
    public function purgeUuid($id);

    /**
     * @param int $id
     * @return void
     */
    public function restoreUuid($id);

    /**
     * @param int $id
     * @return void
     */
    public function trashUuid($id);
}
