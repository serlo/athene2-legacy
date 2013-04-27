<?php

namespace Taxonomy;

use Core\Service\LanguageService;
use Core\Entity\EntityAdapterInterface;
use Core\Entity\EntityInterface;

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
}