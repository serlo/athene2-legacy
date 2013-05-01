<?php

namespace Taxonomy;

interface SharedTaxonomyManagerInterface {
	public function get($name, $languageService = NULL);
	
	/**
	 * @param TaxonomyManagerInterface
	 */
	public function add($name, TaxonomyManagerInterface $manager);
}