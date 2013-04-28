<?php

namespace Taxonomy\Service;

use Core\Entity\ModelInterface;
use Core\Entity\EntityAdapterInterface;
use Core\Entity\EntityInterface;
use Taxonomy\TaxonomyManagerInterface;

interface TermServiceInterface extends ModelInterface, EntityAdapterInterface {
	public function setTaxonomyManager(TaxonomyManagerInterface $termManager);
	public function getTaxonomyManager();
	
	public function build();
	
	/**
	 * Adds an association to a taxonomy term.
	 * Please keep in mind that every association is a m:n relationship and therefore must be written in plural.
	 * You can add a callback function, if you want your results to be modified. The callback receives the arguments `EntityInterface $entity` and `ServiceLocatorInterface $serviceLocator`.
	 * If no callback is specified, you'll receive an `ArrayCollection` of `EntityInterface`s.
	 * 
	 * 		$taxonomy->allowAssociation('entities', function($entity, $serviceLocator){
	 * 			$service = $serviceLocator->get('Some\Service');
	 * 			$service->build($entity);
	 * 			return $service;
	 * 		});
	 * 		$taxonomy->getAssociated('entities');
	 * 
	 * @param string $destination
	 * @param $callback
	 * @return mixed
	 */
	public function allowLink($link, $callback = NULL);

	/**
	 * Returns associated entities.
	 * Please keep in mind that every association is a m:n relationship and therefore must be written in plural.
	 *
	 * 		$taxonomy->getAssociated('comments');
	 *
	 * @param string $destination
	 */
	public function getLinks($link, $service = NULL);
	
	public function addLink($association, EntityInterface $entity);
	public function removeLink($association, EntityInterface $entity);
	
	public function persist($entity = NULL);
	
	public function getParent();
	public function setParent(TermServiceInterface $term);
	public function getChildren();
	public function addChild(TermServiceInterface $term);
}