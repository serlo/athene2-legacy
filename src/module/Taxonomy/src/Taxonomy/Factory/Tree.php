<?php

namespace Taxonomy\Factory;

use Core\Structure\AbstractAdapter;

class Tree extends AbstractAdapter implements FactoryInterface {
	/* (non-PHPdoc)
	 * @see \Taxonomy\Factory\FactoryInterface::build()
	 */
	public function build(\Taxonomy\Service\TaxonomyServiceInterface $adaptee) {
		$this->setAdaptee($adaptee);
	}

	
}