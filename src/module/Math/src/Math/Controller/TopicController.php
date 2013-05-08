<?php

namespace Math\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TopicController extends AbstractActionController {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$view = new ViewModel(array(
			'taxonomies' => array(),
			'entities' => array(
				'exercises' => array(),
			)
		));
		$view->setTemplate('math/index/index');
		return $view;
	}
}