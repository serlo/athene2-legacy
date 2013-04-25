<?php

namespace Taxonomy;

use Taxonomy\Service\TaxonomyServiceInterface;
use Core\Entity\AbstractEntity;

interface TaxonomyManagerInterface {

	/**
	 * @param string $id
	 * @param AbstractEntity $entity
	 * @return TaxonomyServiceInterface
	 */
	public function add($id, AbstractEntity $entity);
	
	/**
	 * 
	 * @param int|string $arg
	 * @param string $slug
	 * @param TaxonomyServiceInterface $parent
	 * @return TaxonomyServiceInterface
	 */
	public function find($arg, $slug = NULL, TaxonomyServiceInterface $parent = NULL);
	
	/**
	 * @param string $id
	 * @return TaxonomyServiceInterface
	 */
	public function get($id);
}