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
	
	public function enableLink($targetField, $callback);
	
	public function getAllLinks();
	public function getLinks($targetField);
	public function addLink($targetField, EntityInterface $entity);
	public function removeLink($targetField, EntityInterface $entity);
}