<?php

namespace Entity\Factory\Components;

use Entity\Factory\EntityServiceProxy;
use Core\Entity\EntityInterface;
use Doctrine\Common\Collections\Collection;

class LinkComponent extends EntityServiceProxy implements ComponentInterface {
	public function build(){
		$entityService = $this->getSource();
		$linkManager = $this->getLinkManager();
		$linkService = $linkManager->create($this->getSource()->getEntity());
	    $entityService->addComponent('link', $linkService);
		return $this;
	}
	
	public function getChildren(){
		$linkService = $this->getComponent('link');
		return $this->_buildFromCollection($linkService->getChildren());
	}

	public function getParents(){
		$linkService = $this->getComponent('link');
		return $this->_buildFromCollection($linkService->getParents());
	}
	
	public function findChildren($factoryClassName){
		$linkService = $this->getComponent('link');
		return $this->_findByFactoryClassName($linkService->getChildren(), $factoryClassName);
	}

	public function findParents($factoryClassName){
		$linkService = $this->getComponent('link');
		return $this->_findByFactoryClassName($linkService->getParents(), $factoryClassName);
	}

	public function findParent($factoryClassName){
		$linkService = $this->getComponent('link');
		return current($this->_findByFactoryClassName($linkService->getParents(), $factoryClassName));
	}
	
	public function findChild($factoryClassName){
		$linkService = $this->getComponent('link');
		return current($this->_findByFactoryClassName($linkService->getChildren(), $factoryClassName));
	}
	
	protected function _findByFactoryClassName(Collection $collection, $factoryClassName){
		$results = array();
		$currentDepth = 1;
		$collection->first();
		foreach($collection->toArray() as $entity){
		    if($entity->get('factory')->get('className') == $factoryClassName ){
			    $results[] = $this->_factory($entity);
		    }
		}
		return $results;
	}
	
	protected function _buildFromCollection(Collection $collection){
		$results = array();
		while(!$collection->isEmpty()){
			$results[] = $this->_factory($collection->current());
			$collection->next();
		}
		return $results;
	}
	
	protected function _factory(EntityInterface $entity){
		return $this->getManager()->get($entity);
	}
}