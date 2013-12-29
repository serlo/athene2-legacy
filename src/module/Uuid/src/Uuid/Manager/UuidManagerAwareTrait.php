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

trait UuidManagerAwareTrait
{

    /**
     *
     * @var \Uuid\Manager\UuidManagerInterface
     */
    protected $uuidManager;

    /**
     *
     * @return \Uuid\Manager\UuidManagerInterface $uuidManager
     */
    public function getUuidManager()
    {
        return $this->uuidManager;
    }

    /**
     *
     * @param \Uuid\Manager\UuidManagerInterface $uuidManager            
     * @return self
     */
    public function setUuidManager(\Uuid\Manager\UuidManagerInterface $uuidManager)
    {
        $this->uuidManager = $uuidManager;
        return $this;
    }
}