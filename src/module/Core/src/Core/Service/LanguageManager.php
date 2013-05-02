<?php

namespace Core\Service;

use Doctrine\ORM\EntityManager;
use Core\Entity\EntityInterface;

class LanguageManager {
	private $_fallBackLanguageId = 1;
	
	private $_languages = array();
	
	/**
	 * @return EntityManager
	 */
	public function getEntityManager() {
		return $this->entityManager;
	}
	
	/**
	 * @param EntityManager $entityManager
	 */
	public function setEntityManager(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}
	
	public function getFallbackLanugage(){
		if(!isset($this->_languages[$this->_fallBackLanguageId])){
			$this->_create($this->_fallBackLanguageId);
		}
		return $this->_languages[$this->_fallBackLanguageId];
	}
	
	private function _add(LanguageService $languageService){
		$this->_languages[$languageService->getId()] = $languageService;
		return $this;
	}
	
	private function _create($id){
		$entity = $this->getEntityManager()->find('Core\Entity\Language', $id);
		$class = new LanguageService();
		$class = $class->setEntity($entity);
		$this->_add($class);
		return $this;
	}
	
	public function getRequestLanguage(){
		return $this->getFallbackLanugage();
	}
	
	public function get($id){
		if(!isset($this->_languages[$id])){
			$this->_create($id);
		}
		return $this->_languages[$id];
	}
	
	public function getByEntity(EntityInterface $entity){
		if(!isset($this->_languages[$entity->getId()])){
			$this->_create($entity->getId());
		}
		return $this->_languages[$entity->getId()];
		
	}
}