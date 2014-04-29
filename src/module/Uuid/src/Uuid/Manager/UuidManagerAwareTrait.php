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

trait UuidManagerAwareTrait
{
    /**
     * @var UuidManagerInterface
     */
    protected $uuidManager;

    /**
     * @return UuidManagerInterface $uuidManager
     */
    public function getUuidManager()
    {
        return $this->uuidManager;
    }

    /**
     * @param UuidManagerInterface $uuidManager
     * @return self
     */
    public function setUuidManager(UuidManagerInterface $uuidManager)
    {
        $this->uuidManager = $uuidManager;

        return $this;
    }
}
