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
namespace Entity\Manager;

trait EntityManagerAwareTrait
{

    /**
     *
     * @var \Entity\Manager\EntityManagerInterface
     */
    protected $entityManager;

    /**
     *
     * @return \Entity\Manager\EntityManagerInterface $entityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     *
     * @param \Entity\Manager\EntityManagerInterface $entityManager            
     * @return self
     */
    public function setEntityManager(\Entity\Manager\EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }
}