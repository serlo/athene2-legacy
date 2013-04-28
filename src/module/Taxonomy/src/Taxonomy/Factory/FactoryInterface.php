<?php

namespace Taxonomy\Factory;

use Taxonomy\Service\TermServiceInterface;

interface FactoryInterface {
	public function build(TermServiceInterface $adaptee);
}