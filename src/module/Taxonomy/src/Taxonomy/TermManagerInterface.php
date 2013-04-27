<?php

namespace Taxonomy;

use Taxonomy\Service\TermServiceInterface;
use Core\Entity\EntityInterface;
use Taxonomy\Service\TaxonomyServiceInterface;
use Core\Entity\ModelInterface;

interface TermManagerInterface extends ModelInterface {
	
	/**
	 * Returns a term by its id
	 * 
	 * @param TermServiceInterface $term
	 */
	public function get($id);
	
	/**
	 * Finds a term by it's path (slugs!).
	 * 
	 * 		$path = 'path/to/somewhere';
	 * 		$pathArray = explode($path, '/'); // array('path', 'to', 'my', 'term')
	 * 		$termManager->find($pathArray);
	 * 
	 * Provide a parent, to seek into the path.
	 * 
	 * 		$path = 'path/to/somewhere';
	 * 		$pathArray = explode($path, '/');
	 * 		$parent = $termManager->find(array($pathArray[0])); // array('path')
	 * 		$term = $termManager->find(array($pathArray[0], $pathArray[1]), $parent); // array('to', 'somewhere')
	 * 
	 * @param array $path
	 * @param TaxonomyServiceInterface $parent
	 * @param TermServiceInterface $term
	 */
	public function find(array $path, TermServiceInterface $parent = NULL);
	
	/**
	 * Creates a term.
	 * Data should contain at least: 'name' and 'slug'.
	 * 
	 * 		$termManager->create(array(
	 * 			'name' => 'Term name'
	 * 			'slug' => 'unique-name-in-path-213'
	 * 		));
	 * 
	 * @param array $data
	 * @param TermServiceInterface $term
	 */
	public function create(array $data);
	
	/**
	 * Deletes a term.
	 * 
	 * @param TermServiceInterface $term
	 * @return $this
	 */
	public function delete(TermServiceInterface $term);
	
	/**
	 * Adds a term to the manager.
	 * 
	 * @param TermServiceInterface $term
	 * @return $this 
	 */
	public function add(TermServiceInterface $term);
	
	/**
	 * Sets the TaxonomyManager
	 * 
	 * @param TaxonomyManagerInterface $taxonomyManager
	 * @return $this
	 */
	public function setTaxonomyManager(TaxonomyManagerInterface $taxonomyManager);
	
	/**
	 * Gets the TaxonomyManager
	 * @return TaxonomyManagerInterface
	 */
	public function getTaxonomyManager();
	
	/**
	 * Sets the language of this type
	 * 
	 * @param EntityInterface $language
	 * @return $this
	 */
	public function setLanguage(EntityInterface $language);
	
	/**
	 * Returns the language
	 * 
	 * @return EntityInterface $language
	 */
	public function getLanguage();
}