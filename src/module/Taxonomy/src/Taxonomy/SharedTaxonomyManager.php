<?php

namespace Taxonomy;

interface SharedTaxonomyManagerInterface {
	public function get();
	public function getShared();
	
	public function add();
	public function addShared();
}