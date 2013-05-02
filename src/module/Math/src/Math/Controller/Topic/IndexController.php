<?php

namespace Math\Controller\Topic;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Taxonomy\TaxonomyManagerInterface;

class IndexController extends AbstractActionController {

	/**
	 * 
	 * @var TaxonomyManagerInterface
	 */
	protected $_taxonomyManager;
	
	/**
	 * @return TaxonomyManagerInterface $_taxonomyManager
	 */
	public function getTaxonomyManager() {
		return $this->_taxonomyManager;
	}

	/**
	 * @param TaxonomyManagerInterface $_taxonomyManager
	 */
	public function setTaxonomyManager(TaxonomyManagerInterface $_taxonomyManager) {
		$this->_taxonomyManager = $_taxonomyManager;
		return $this;
	}

	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$tm = $this->getTaxonomyManager();
		$ts = $tm->get('subject', array('mathe'));
		return "";
	}
}