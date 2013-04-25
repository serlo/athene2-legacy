<?php
namespace Entity\Factory;

use Entity\Service\EntityServiceInterface;

interface EntityFactoryInterface {
	public function __construct();
	public function build(EntityServiceInterface $entityService);
}