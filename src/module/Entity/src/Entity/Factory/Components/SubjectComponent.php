<?php

namespace Entity\Factory\Components;

use Entity\Factory\EntityServiceProxy;

class SubjectComponent extends EntityServiceProxy implements ComponentInterface {
	public function __construct($source){
		$this->setSource($source);
	}
	
	public function build(){
        $source = $this->getSource();
        
        $languageManager = $this->getLanguageManager();
        $languageService = $languageManager->getByEntity($source->get('language'));
        $sharedTaxonomyManager = $this->getSharedTaxonomyManager();
        
        // TODO: Write Language Manager!
        $taxonomyManager = $sharedTaxonomyManager->get('subject', $languageService);
	    $this->addComponent('subjectTaxonomy', $taxonomyManager);
        return $this;
	}
	
	public function getSubject(){
		$taxonomyManager = $this->getComponent('subjectTaxonomy');
		return $taxonomyManager->getTermByLink('entities', $this->getSource()->getEntity())->get('name');
	}
	
	public function hasSubject($subjectName){
		return $this->getSubject() == $subjectName;
	}
}