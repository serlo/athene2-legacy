<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Metadata\Manager;

use Metadata\Entity;
use Uuid\Entity\UuidInterface;
use Doctrine\Common\Collections\Collection;

interface MetadataManagerInterface
{

    /**
     * @param int $id
     * @return Entity\MetadataInterface
     */
    public function getMetadata($id);

    /**
     * @param int $id
     * @return void
     */
    public function removeMetadata($id);

    /**
     * @param UuidInterface $object
     * @return Entity\MetadataInterface[]|Collection
     */
    public function findMetadataByObject(UuidInterface $object);

    /**
     * @param UuidInterface $object
     * @param string        $key
     * @param string        $value
     * @return Entity\MetadataInterface|Collection
     */
    public function addMetadata(UuidInterface $object, $key, $value);

    /**
     * @param UuidInterface $object
     * @param string        $key
     * @return Entity\MetadataInterface[]|Collection
     */
    public function findMetadataByObjectAndKey(UuidInterface $object, $key);

    /**
     * @param \Uuid\Entity\UuidInterface $object
     * @param string                     $key
     * @param string                     $value
     * @return Entity\MetadataInterface
     */
    public function findMetadataByObjectAndKeyAndValue(UuidInterface $object, $key, $value);
}
