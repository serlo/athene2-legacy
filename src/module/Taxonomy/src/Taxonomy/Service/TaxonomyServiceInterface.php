<?php
namespace Taxonomy\Service;

interface TaxonomyServiceInterface {
	/**
	 * Adds an association to a taxonomy term.
	 * Please keep in mind that every association is a m:n relationship and therefore must be written in plural.
	 * 
	 * 		$taxonomy->addAssociation('entities');
	 * 		$taxonomy->getAssociated('entities');
	 * 
	 * @param string $destination
	 */
	public function addAssociation($destination);

	/**
	 * Returns associated entities.
	 * Please keep in mind that every association is a m:n relationship and therefore must be written in plural.
	 *
	 * 		$taxonomy->getAssociated('comments');
	 *
	 * @param string $destination
	 */
	public function getAssociated($destination);
}