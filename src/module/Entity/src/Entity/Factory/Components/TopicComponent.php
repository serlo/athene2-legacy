<?php
namespace Entity\Factory\Components;

use Entity\Factory\EntityServiceProxy;

class TopicComponent extends EntityServiceProxy implements ComponentInterface {
	public function __construct($source){
		$this->setSource($source);
	}
	
	public function build(){
        $source = $this->getSource();
        
        $languageManager = $this->getLanguageManager();
        $languageService = $languageManager->getByEntity($source->get('language'));
        $sharedTaxonomyManager = $this->getSharedTaxonomyManager();
        
        $taxonomyManager = $sharedTaxonomyManager->get('topicFolder', $languageService);
	    $this->addComponent('topicTaxonomy', $taxonomyManager);
        return $this;
	}
	
	public function getTopic(){
		$taxonomyManager = $this->getComponent('topicTaxonomy');
		return $taxonomyManager->getTermByLink('entities', $this->getSource()->getEntity())->get('name');
	}
}