<?php

namespace module\Taxonomy\src\Taxonomy\Service;

use Core\Entity\EntityInterface;
interface TermServiceInterface {
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
	
	public function build();
}