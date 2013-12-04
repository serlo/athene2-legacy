<?php
namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Page\Form\RepositoryForm;
use Page\Form\RevisionForm;
use User\Service\UserService;
use Page\Exception\PageNotFoundException;
use Zend\Form\Form;

class IndexController extends AbstractActionController
{
    use\Language\Manager\LanguageManagerAwareTrait;
    use \Page\Manager\PageManagerAwareTrait;
    use \Common\Traits\ObjectManagerAwareTrait;
    use\User\Manager\UserManagerAwareTrait;
    
    
    public function indexAction()
    {
        $repositorys = $this->getPageManager()->findAllRepositorys($this->getLanguageManager()
            ->getLanguageFromRequest());
        $view = new ViewModel(array(
            'repositorys' => $repositorys
        ));
        $view->setTemplate('page/pages');
        return $view;
    }
    

    public function setCurrentRevisionAction()
    {
        $id = $this->params('id');
        $pageService = $this->getPageService();
        $pageService->setCurrentRevision($this->getPageManager()->getRevision($id));
        $this->redirect()->toRoute('page/article', array(
            'repositoryid' =>  $pageService->getRepositoryId()
        ));
        $this->getObjectManager()->persist($pageService->getEntity());
        $this->getObjectManager()->flush();
    }

    public function showRevisionsAction()
    {
        $pageService = $this->getPageService();
        $repository = $pageService->getEntity();
        $revisions = $repository->getRevisions();
        $view = new ViewModel(array(
            'revisions' => $revisions,
            'repositoryid' => $pageService->getRepositoryId()
        ));
        $view->setTemplate('page/show-revisions.phtml');
        return $view;
    }

    public function showRevisionAction()
    {
        $id = $this->params('id');
        $pageService = $this->getPageService();
        $revision = $this->getPageManager()->getRevision($id);
        $view = new ViewModel(array(
            'revision' => $revision,
            'repositoryid' =>  $pageService->getRepositoryId()
        ));
        
        $view->setTemplate('page/revision.phtml');
        return $view;
    }

    public function editRepositoryAction()
    {
        $form = new RepositoryForm($this->getObjectManager());
        
        $language_id = $this->getLanguageManager()
            ->getLanguageFromRequest()
            ->getId();
        $pageService = $this->getPageService();
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                $this->getObjectManager()->flush();
                $this->redirect()->toRoute('page/article', array(
                    'repositoryid' =>  $pageService->getRepositoryId()
                ));
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form,
            'title' => 'Repository bearbeiten'
        ));
        $view->setTemplate('page/form.phtml');
        return $view;
    }

    public function createRevisionAction()
    {
        $us = $this->getUserManager()->getUserFromAuthenticator();
        $form = new RevisionForm($this->getObjectManager());
        $id = $this->params('id');
        $language = $this->getLanguageManager()
        ->getLanguageFromRequest();
        $language_id = $language->getId();
        $pageService = $this->getPageService();

        $repository = $pageService->getEntity();
        if ($id != NULL) {
            $form->get('content')->setValue($this->getPageManager()->getRevision($id)
                ->getContent());
            $form->get('title')->setValue($this->getPageManager()->getRevision($id)
                ->getTitle());
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                $array['author'] = $this->getUserManager()->getUserFromAuthenticator()->getEntity();
                $page = $this->getPageManager()->createRevision($repository, $array);
                $this->getObjectManager()->flush();
                
            
                
                $this->redirect()->toRoute('page/article',array('repositoryid'=>$pageService->getRepositoryId()));
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form,
            'title' => 'Revision erstellen'
        ));
        $view->setTemplate('page/form.phtml');
        return $view;
    }

    public function createRepositoryAction()
    {
        $language = $this->getLanguageManager()
            ->getLanguageFromRequest();
        $form = new RepositoryForm($this->getObjectManager());
               
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                $repository = $this->getPageManager()->createPageRepository($form->getData(), $language->getEntity());

               
                $this->getEventManager()->trigger('page.create', $this, array(
                    'repositoryid' => $repository->getRepositoryId(),
                    'language' => $language,
                    'repository' => $repository->getEntity(),
                	'slug' => $array['slug']
                
                ));
                
                
                $this->getObjectManager()->flush();
                $this->redirect()->toRoute('page/article/createrevision',array('repositoryid'=>$repository->getRepositoryId()));
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form,
            'title' => 'Seite erstellen'
        ));
        
        $view->setTemplate('page/form.phtml');
        return $view;
    }
    
    public function trashRevisionAction()
    {
        $pageService = $this->getPageService();
        $id = $this->params('revisionid');
        
        $pageService->trashRevision($id);
        $this->redirect()->toRoute('page/article/revisions',array('repositoryid'=>$this->params('repositoryid')));
    }

    public function deleteRevisionAction()
    {
        $id = $this->params('revisionid');
        $pageService = $this->getPageService();
        $pageService->deleteRevision($id);
        $this->redirect()->toRoute('page/article',array('repositoryid'=>$this->params('repositoryid')));
        $this->getObjectManager()->flush();
    }

    public function deleteRepositoryAction()
    {
      
        $pageService = $this->getPageService();
        $repository = $pageService->getEntity();
        $pageService->getRepositoryManager()->removeRepository($repository);
        $this->getObjectManager()->persist($repository);
        $this->getObjectManager()->remove($repository);
        $this->getObjectManager()->flush();
                $this->redirect()->toRoute('page');
    }

    public function trashRevision(){
        $id = $this->params('revisionid');
        $pageService = $this->getPageService();
        $pageService->trashRevision($id);
        $this->redirect()->toRoute('page/article',array('repositoryid'=>$this->params('repositoryid')));
        $this->getObjectManager()->flush();
    }
    public function articleAction()
    {
       
        $pageService = $this->getPageService();
        if ($pageService->hasCurrentRevision()) {
            
            $revision = $pageService->getCurrentRevision();
            $title = $revision->getTitle();
            $content = $revision->getContent();
            $revisionid = $revision->getId();
        } else
             $revision = NULL;
        
      
      
        $view = new ViewModel(array(
            'revision' => $revision,
            'repositoryid' =>  $pageService->getRepositoryId()
        ));
        
        $view->setTemplate('page/revision.phtml');
        
        return $view;
    }
    
    protected function getPageService(){
        $id = $this->params('repositoryid');
        return
        $this->getPageManager()->getPageRepository($id);
    }
}
