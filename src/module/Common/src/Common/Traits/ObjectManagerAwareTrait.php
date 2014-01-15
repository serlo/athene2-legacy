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
namespace Common\Traits;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

trait ObjectManagerAwareTrait
{

    /**
     *
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     *
     * @return \Doctrine\Common\Persistence\ObjectManager $objectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager            
     * @return self
     */
    public function setObjectManager(EntityManager $objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }
}