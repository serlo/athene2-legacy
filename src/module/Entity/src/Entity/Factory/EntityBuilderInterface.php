<?php
namespace Entity\Factory;

interface EntityBuilderInterface {
	public function build(EntityFactoryInterface $entityService);
}