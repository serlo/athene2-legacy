<?php

namespace Taxonomy\Factory;

use Taxonomy\Service\TaxonomyServiceInterface;

interface FactoryInterface {
	public function build(TaxonomyServiceInterface $adaptee);
}