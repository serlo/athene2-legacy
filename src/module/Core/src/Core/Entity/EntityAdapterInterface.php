<?php

namespace Core\Entity;

interface EntityAdapterInterface {
	public function __construct(EntityInterface $entity = NULL);
	public function setEntity(EntityInterface $entity);
	public function getEntity();
}