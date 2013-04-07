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
        $ps->prepareRevision($id);
        $this->title()->set($ps->get('title'));

        //$ps->checkoutRevision(1, 3);
        return new ViewModel(array(
            'content' => $ps->get('content')
        ));
    }
}