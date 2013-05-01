<?php
namespace Core\Service;
use Doctrine\ORM\EntityManager;
use Core\Entity\EntityInterface;
use Core\Entity\AbstractEntityAdapter;

/**      
 */
class LanguageService extends AbstractEntityAdapter
{
    private $entityManager;
	private $entity;
	
	private $fallBackLanguage = 1;
	
	private $isClone = false;
    
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
    
	/*public function setEntity(EntityInterface $entity){
		$this->entity = $entity;
		return $this;
	}
	
	public function get($field){
		if($field instanceof EntityInterface){
	    	if(!$this->isClone)
	    		throw new \Exception('Use the manager!');
	    		
			$this->setEntity($field);
			return $this;
		} else {
			return parent::get($field);
		}
	}*/
	
    public function getEntity(){
    	/*if($this->entity === NULL){
	        $this->setEntity($this->getEntityManager()->find('Core\Entity\Language', $this->fallBackLanguage));
    	}
    	return $this->entity;*/
    	return $this->getEntityManager()->find('Core\Entity\Language', $this->fallBackLanguage);
    }
}