<?php

namespace Core\Entity;

use Core\Structure\AdapterInterface;

interface EntityAdapterInterface extends AdapterInterface {
	public function setEntity(EntityInterface $entity);
	public function getEntity();
}