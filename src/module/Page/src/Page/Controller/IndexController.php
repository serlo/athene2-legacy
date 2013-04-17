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
            'title' => $ps->get('title'),
            'content' => $ps->get('content'),
            'date' => $ps->get('date')->format($this->dateFormat()),
            'author' => $ps->get('author')->get('username'),
            'hrefs' => array(
                'delete' => $this->url()->fromRoute('pageDelete', array("id" => $id)),
                'administrate' => $this->url()->fromRoute('pageAdministrate', array("id" => $id)),
                'update' => $this->url()->fromRoute('pageUpdate', array("id" => $id))
            )
        ));
    }
}