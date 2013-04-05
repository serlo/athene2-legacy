<?php
namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Page\Service\PageServiceInterface;

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
        $id = $this->getParam('slug');
        $ps = $this->getPageService();
        $this->title()->set($ps->get($id, 'title'));
        
        return new ViewModel(array(
            'content' => $ps->get($id, 'content')
        ));
    }
}