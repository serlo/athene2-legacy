<?php
namespace Entity\Factory;

use Entity\Service\EntityServiceInterface;
use Core\Entity\AbstractEntityAdapter;
use Entity\Component\RenderComponent;
use Entity\Component\RenderService;

abstract class AbstractEntityFactory extends AbstractEntityAdapter {
	
	/**
	 * @var EntityServiceInterface
	 */
	protected $adaptee;
	
	/**
	 * @param EntityServiceInterface $adaptee
	 * @return $this
	 */
	public function build(EntityServiceInterface $adaptee){
		$this->setAdaptee($adaptee);
		
	    $this->uniqueName = 'Entity('.$this->getId().')';
	    $this->_loadComponents();
		return $this;
	}
	
	abstract protected function _loadComponents();
	
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
	    $repository = $entityService->getEntity();
	    $repository = $this->getRepositoryManager()->addRepository($this->uniqueName, $repository);
	    $entityService->addComponent('repository', $repository);
	    return $this;
	}
	
	public function addRenderComponent($template){
        $entityService = $this->getAdaptee();
        $render = new RenderService($template);
	    $entityService->addComponent('render', $render);
	    return $this;	    
	}
	
	public function addSubjectComponent(){
        $entityService = $this->getAdaptee();
        $languageManager = $this->getLanguageManager();
        $languageService = $languageManager->get($this->get('language'));
        $sharedTaxonomyManager = $this->getSharedTaxonomyManager();
        $taxonomyManager = $sharedTaxonomyManager->get('subject', $languageService);
	    $entityService->addComponent('subjectTaxonomy', $taxonomyManager);
        return $this;
	}
	
	public function getSubject(){
		$taxonomyManager = $this->getComponent('subjectTaxonomy');
		$taxonomyManager->getTerm($this->getEntity());
	}
	
	public function setSubject(){
		
	}
	
	public function getComponent($name){
	    return $this->getAdaptee()->getComponent($name);
	}
	
	public function getSharedTaxonomyManager(){
		return $this->getAdaptee()->getSharedTaxonomyManager();
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