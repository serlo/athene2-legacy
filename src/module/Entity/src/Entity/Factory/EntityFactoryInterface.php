<?php
namespace Entity\Factory;

use Entity\Service\EntityServiceInterface;

interface EntityFactoryInterface {
	public function build(EntityServiceInterface $entityService);
}