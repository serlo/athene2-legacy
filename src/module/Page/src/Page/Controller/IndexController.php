<?php
namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController, Page\Service\PageServiceInterface, Zend\View\Helper\ViewModel;

class IndexController extends AbstractActionController
{

    private $pageService;
        
    /**
     *
     * @return the $pageService
     */
    public function getPageService ()
    {
        return $this->pageService;
    }

    /**
     *
     * @param PageServiceInterface $pageService            
     */
    public function setPageService (PageServiceInterface $pageService)
    {
        $this->pageService = $pageService;
    }

    public function indexAction ()
    {
        $this->title()->set('Static');
        $id = $this->getParam('slug');
        $ps = $this->getPageService();

        return new ViewModel(array(
            'title' => 'herp',
            'content' => 'derp'
        ));
    }
}