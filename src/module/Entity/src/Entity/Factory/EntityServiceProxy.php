<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\Factory;

use Core\Structure\AbstractProxy;
use Entity\Service\EntityService;
use Core\Entity\EntityInterface;

class EntityServiceProxy extends AbstractProxy {
    
    /**
     * (non-PHPdoc)
     * @see \Core\Structure\AbstractProxy::setSource()
     */
    public function setSource($source){
        return $this->_setSource($source);
    }
    
    /**
     * Sets the source
     * 
     * @param EntityFactoryInterface $entity
     * @return $this;
     */
    private function _setSource(EntityFactoryInterface $entity){
        $this->source = $entity;
        return $this;
    }
    
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

	/**
	 * @see \Entity\Service\EntityService::getEntity()
	 */
	public function getEntity(){
	    return $this->getSource()->getEntity();
	}

	/**
	 * @see \Entity\Service\EntityService::getFactoryClassName()
	 */
	public function getFactoryClassName(){
	    return $this->getSource()->getFactoryClassName();	    
	}
	
	/**
	 * @see \Entity\Service\EntityService::getId()
	 */
	public function getId(){
	    return $this->getSource()->getId();
	}
}