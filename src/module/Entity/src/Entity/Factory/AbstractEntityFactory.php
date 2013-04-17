<?php
namespace Entity\Factory;

use Entity\Service\EntityServiceInterface;
use Core\Entity\AbstractEntityAdapter;
use Entity\Component\RenderComponent;

abstract class AbstractEntityFactory extends AbstractEntityAdapter {
	
	/**
	 * @var EntityServiceInterface
	 */
	protected $adaptee;
	
	protected $ormClassNames = array();
	
	/**
	 * @param EntityServiceInterface $prototype
	 * @return $this
	 */
	public function __construct(EntityServiceInterface $adaptee){
		$this->adaptee = $adaptee;
	    $this->setAdaptee($adaptee);
	    $this->uniqueName = 'Entity('.$this->getId().')';
	    $this->ormClassNames = array(
	        'entity' => 'Entity\Entity\Entity',
	        'repository' => 'Entity\Entity\Repository'
	    );
	    $this._loadComponents();
		return this;
	}
	
	abstract protected function _loadComponents();

	/**
	 * @param EntityServiceInterface $prototype
	 * @return $this
	 */
	public function setAdaptee(EntityServiceInterface $adaptee = NULL){
		$this->adaptee = $adaptee;		
		return this;
	}
	
	/**
	 * @return EntityServiceInterface
	 */
	public function getAdaptee(){
		if($this->adaptee === NULL)
			throw new \Exception('Adaptee not set!');

		return $this->adaptee;
	}
	
	public function addRepositoryComponent(){
        $entityService = $this->getAdaptee();
	    $repository = $entityService->get('repository');
	    $repository = $this->getRepositoryManager()->addRepository($this->uniqueName, $repository);
	    $entityService->addComponent('repository', $repository);
	    return $this;
	}
	
	public function addRenderComponent($template){
        $entityService = $this->getAdaptee();
        $render = new RenderComponent($template);
	    $entityService->addComponent('render', $render);
	    return $this;	    
	}
	
	public function getComponent($name){
	    return $this->getAdaptee()->getComponent($name);
	}
	
	public function getRepositoryManager(){
	    return $this->getAdaptee()->getRepositoryManager();
	}
	
	public function getEventManager(){
	    return $this->getAdaptee()->getEventManager();	    
	}
	
	public function getEntityManager(){
	    return $this->getAdaptee()->getEntityManager();	    
	}
	
	public function getAuthService(){
	    return $this->getAdaptee()->getAuthService();
	}
	
	public function getLanguageService(){
	    return $this->getAdaptee()->getLanguageService();
	}
	
	public function getSubjectService(){
	    return $this->getAdaptee()->getSubjectService();	    	    
	}
}