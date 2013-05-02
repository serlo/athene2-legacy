<?php

namespace Entity\Factory\Components;

use Entity\Factory\Components\ComponentInterface;
use Entity\Factory\EntityServiceProxy;

class RepositoryComponent extends EntityServiceProxy implements ComponentInterface {
	public function build(){
		$entityService = $this->getSource();
		$repository = $entityService->getEntity();
		$repository = $this->getRepositoryManager()->addRepository('Entity('.$entityService->getId().')', $repository);
		$entityService->addComponent('repository', $repository);
		return $this;
	}
	
	/*public function getContent(){
		return $this->getComponent('repository')->getCurrentRevision()->get('content');
	}
	
	public function getTitle(){
		return $this->getComponent('repository')->getCurrentRevision()->get('title');
	}
	
	public function getSummary(){
		return $this->getComponent('repository')->getCurrentRevision()->get('summary');
	}*/

	public function getCurrentRevision(){
		return $this->getComponent('repository')->getCurrentRevision();
	}
	
	public function addRevision(array $data){
		
	}
}