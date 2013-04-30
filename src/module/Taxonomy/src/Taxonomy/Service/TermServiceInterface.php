<?php

namespace Taxonomy\Service;

use Core\Entity\EntityInterface;
use Core\Entity\EntityAdapterInterface;

interface TermServiceInterface extends EntityAdapterInterface {
	public function setTemplate($template);
	public function getViewModel();
	
	public function getParent();
	public function getChildren();
	
	public function toArray();
	
	public function linkingAllowed($targetField);
	
	public function getAllLinks();
	public function getLinks($targetField);
	public function addLink($targetField, EntityInterface $entity);
	public function removeLink($targetField, EntityInterface $entity);
}