<?php

namespace Taxonomy;

interface SharedTaxonomyManagerInterface {
	public function get($id);
	
	/**
	 * @param TaxonomyManagerInterface
	 */
	public function add(TaxonomyManagerInterface $manager);
}