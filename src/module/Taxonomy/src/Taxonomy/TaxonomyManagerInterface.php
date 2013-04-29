<?php

namespace Taxonomy;

interface TaxonomyManagerInterface {
	public function addTerm();
	public function createTerm();
	public function hasTerm();
	
	/**
	 * 
	 * @param int|array an id or a path
	 */
	public function getTerm($id);
	
	public function getTerms($filter = NULL);
	
	public function toArray();
	
	public function setTemplate($template);
	public function setTermTemplate($template);
}