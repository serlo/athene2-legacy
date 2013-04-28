<?php

namespace Taxonomy;

use Core\Service\LanguageService;
use Core\Entity\EntityInterface;
use Taxonomy\Service\TermServiceInterface;

interface TaxonomyManagerInterface {
	/**
	 * Finds an TermManager, by its type and language
	 * 
	 * @param unknown $type
	 * @param LanguageService $languageService
	 * @return TermManagerInterface
	 */
	public function find($type, LanguageService $languageService = NULL);
	
	/**
	 * Sets a default Language Service
	 * 
	 * @param LanguageService $languageService
	 * @return this
	 */
	public function setLanguageService(LanguageService $languageService);
	
	/**
	 * Adds a termManager
	 * 
	 * @param EntityInterface $entity
	 * @param TermManagerInterface $termManager
	 * @return $this
	 */
	public function add(EntityInterface $entity, TermManagerInterface $termManager);
	
	public function getAllTerms();
	
	public function addTerm(TermServiceInterface $term);
	public function removeTerm(TermServiceInterface $term);
	
	public function getTermsByLink($with, EntityInterface $entity);
	
	public function getTerm($id);
	public function getTermBySlugs(array $slug);
}