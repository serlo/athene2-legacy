<?php

namespace Taxonomy\Factory;

use Core\Structure\AbstractAdapter;
use Taxonomy\TaxonomyManagerInterface;

class Tree extends AbstractAdapter implements FactoryInterface {
	/* (non-PHPdoc)
	 * @see \Taxonomy\Factory\FactoryInterface::build()
	 */
	public function build(TaxonomyManagerInterface $adaptee) {
		return $this;
	}
	
	public function getServiceLocator(){
		return $this->getAdaptee()->getServiceLocator();
	}
}