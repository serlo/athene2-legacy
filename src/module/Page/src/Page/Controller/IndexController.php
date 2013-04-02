<?php

namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController,
Page\Service\PageServiceInterface;
use Zend\View\Helper\ViewModel;

class IndexController extends AbstractActionController
{
    private $pageService;
    
    /**
	 * @return the $pageService
	 */
	public function getPageService() {
		return $this->pageService;
	}

	/**
	 * @param PageServiceInterface $pageService
	 */
	public function setPageService(PageServiceInterface $pageService) {
		$this->pageService = $pageService;
	}

	public function indexAction(){
        $id = $this->getParams('id');
        $page = $this->getPageService()->receive($id);
        return new ViewModel(array(
            'title' => $page->get('title'),
            'content' => $page->get('content')
        ));
    }
}