<?php

namespace Taxonomy\Factory;

use Taxonomy\TaxonomyManagerInterface;

interface FactoryInterface {
	public function build(TaxonomyManagerInterface $adaptee);
}