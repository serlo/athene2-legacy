<?php

namespace Entity\Factory;

use Core\Structure\AbstractProxy;
use Entity\Service\EntityService;

class EntityServiceProxy extends AbstractProxy {
	/**
	 * @see \Entity\Service\EntityService::addComponent()
	 */
	protected function addComponent($name, $component){
		return $this->getSource()->addComponent($name, $component);
	}

	/**
	 * @see \Entity\Service\EntityService::getComponent()
	 */
	public function getComponent($name){
		return $this->getSource()->getComponent($name);
	}

	/**
	 * @see \Entity\Service\EntityService::getSharedTaxonomyManager()
	 */
	public function getSharedTaxonomyManager(){
		return $this->getSource()->getSharedTaxonomyManager();
	}

	/**
	 * @see \Entity\Service\EntityService::getRepositoryManager()
	 */
	public function getRepositoryManager(){
		return $this->getSource()->getRepositoryManager();
	}

	/**
	 * @see \Entity\Service\EntityService::getLanguageManager()
	 */
	public function getLanguageManager(){
		return $this->getSource()->getLanguageManager();
	}

	/**
	 * @see \Entity\Service\EntityService::getEventManager()
	 */
	public function getEventManager(){
		return $this->getSource()->getEventManager();
	}

	/**
	 * @see \Entity\Service\EntityService::getEntityManager()
	 */
	public function getEntityManager(){
		return $this->getSource()->getEntityManager();
	}

	/**
	 * @see \Entity\Service\EntityService::getManager()
	 */
	public function getManager(){
		return $this->getSource()->getManager();
	}

	/**
	 * @see \Entity\Service\EntityService::getAuthService()
	 */
	public function getAuthService(){
		return $this->getSource()->getAuthService();
	}

	/**
	 * @see \Entity\Service\EntityService::getLanguageService()
	 */
	public function getLanguageService(){
		return $this->getSource()->getLanguageService();
	}

	/**
	 * @see \Entity\Service\EntityService::getSubjectService()
	 */
	public function getSubjectService(){
		return $this->getSource()->getSubjectService();
	}
	
	/**
	 * @see \Entity\Service\EntityService::getLinkManager()
	 */
	public function getLinkManager(){
		return $this->getSource()->getLinkManager();
	}
}