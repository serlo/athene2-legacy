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
namespace Uuid\Entity;

use Markdown\Entity\CacheableInterface;

interface UuidHolder extends CacheableInterface
{

    /**
     *
     * @return int
     */
    public function getId();

    /**
     *
     * @return string
     */
    public function getUuid();

    /**
     *
     * @return string
     */
    public function getHolderName();

    /**
     *
     * @return UuidInterface
     */
    public function getUuidEntity();

    /**
     *
     * @return bool
     */
    public function getTrashed();

    /**
     *
     * @param string $trashed            
     * @return self
     */
    public function setTrashed($trashed);

    /**
     *
     * @param UuidInterface $uuid            
     * @return self
     */
    public function setUuid(UuidInterface $uuid);
}