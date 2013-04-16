<?php
namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Page\Service\PageServiceInterface;
use Page\Form\Page as PageForm;

class PageController extends AbstractActionController
{

    private $pageService;

    /**
     *
     * @param PageServiceInterface $pageService            
     */
    public function setPageService (PageServiceInterface $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     *
     * @return PageServiceInterface
     */
    public function getPageService ()
    {
        return $this->pageService;
    }

    public function createAction ()
    {
        $ps = $this->getPageService();
        $form = new PageForm();
        $form->setAttribute('action', $this->url()
            ->fromRoute('pageCreate'));
        
        $this->title()->set($this->translate('Seite') . ' <small>' . $this->translate('erstellen') . '</small>');
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('page/page/form');
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $page = $ps->create($form->getData());
                $revision = $ps->addRevision($page->getId(), $form->getData());

                $ps->checkoutRevision($page->getId(), $revision->getId());
                
                $ps->prepareRevision($page->getId());
                $this->flashMessenger()->addSuccessMessage("Seite erfolgreich erstellt!");
                $this->redirect()->toRoute('pageShow', array(
                    'slug' => $page->get('id')
                ));
            } else {
                return $view;
            }
        } else {
            return $view;
        }
    }

    public function deleteAction ()
    {
        $id = $this->getParam('id');
        $ps = $this->getPageService();
    }

    public function checkoutRevisionAction ()
    {
        $id = $this->getParam('id');
        $rid = $this->getParam('rid');
        $ps = $this->getPageService();
    }

    public function updateAction ()
    {
        $id = $this->getParam('id');
        $ps = $this->getPageService();
        $ps->prepareRevision($id);
        $form = new PageForm();
        
        $form->setAttribute('action', $this->url()
            ->fromRoute('pageUpdate', array(
            'id' => $id
        )));

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if($form->isValid()){
                $revision = $ps->addRevision($id, $form->getData());

                $ps->checkoutRevision($id, $revision->getId());
                
                $ps->prepareRevision($id);
                $this->flashMessenger()->addSuccessMessage("Seite erfolgreich bearbeitet!");
                $this->redirect()->toRoute('pageShow', array(
                    'slug' => $id
                ));                
            }
        } else {
            $form->setData(array(
                'title' => $ps->get('title'),
                'content' => $ps->get('content')
            ));
        }
        
        
        $view = new ViewModel(array(
            'form' => $form,
            'title' => $ps->get('title') . ' <small>' . $this->translate('bearbeiten') . '</small>',
        ));
        $view->setTemplate('page/page/form');        
        return $view;
    }
}