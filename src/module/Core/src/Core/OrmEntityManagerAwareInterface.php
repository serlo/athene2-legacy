<?php

namespace Core;

use Doctrine\ORM\EntityManager;
interface OrmEntityManagerAwareInterface {
	
	/**
	 * Sets the ORM EntityManager
	 * 
	 * @param EntityManager $entityManager
	 * @return $this
	 */
	public function setEntityManager(EntityManager $entityManager);
	
	/**
	 * Returns the ORM EntityManager
	 * 
	 * @return EntityManager
	 */
	public function getObjectManager();
}