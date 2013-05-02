<?php

namespace Entity\Factory\Components;

class RenderComponent extends AbstractComponent {
	
	public function build($template){
        $entityService = $this->getAdaptee();
        $render = new RenderService($template);
	    $entityService->addComponent('render', $render);
	    return $this;	    
	}	
}