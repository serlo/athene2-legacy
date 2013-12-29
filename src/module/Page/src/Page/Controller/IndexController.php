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
        $pageRepository = $this->getPageRepository();
        $pageRepository->setCurrentRevision($this->getPageManager()->getRevision($id));
        $this->redirect()->toReferer();
        $this->getObjectManager()->persist($pageRepository);
        $this->getObjectManager()->flush();
    }

    public function showRevisionsAction()
    {
        $pageRepository = $this->getPageRepository();
        $revisions = $pageRepository->getRevisions();
        $view = new ViewModel(array(
            'revisions' => $revisions,
            'repositoryid' => $pageRepository->getId()
        ));
        $view->setTemplate('page/show-revisions.phtml');
        return $view;
    }

    public function showRevisionAction()
    {
        $id = $this->params('id');
        $pageRepository = $this->getPageRepository();
        $revision = $this->getPageManager()->getRevision($id);
        $view = new ViewModel(array(
            'revision' => $revision,
            'repositoryid' =>  $pageRepository->getId()
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
        $pageRepository = $this->getPageRepository();
        
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                $this->getPageManager()->editPageRepository($array,$pageRepository);
                $this->getObjectManager()->flush();
                $this->redirect()->toRoute('page/article', array(
                    'repositoryid' =>  $pageRepository->getId()
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
        $pageRepository = $this->getPageRepository();
        
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
                $page = $this->getPageManager()->createRevision($pageRepository, $array);
                $this->getObjectManager()->flush();
                
                $this->redirect()->toRoute('page/article',array('repositoryid'=>$pageRepository->getId()));
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
                    'repositoryid' => $repository->getId(),
                    'language' => $language,
                    'repository' => $repository,
                	'slug' => $array['slug']
                
                ));
                
                
                $this->getObjectManager()->flush();
                $this->redirect()->toRoute('page/article/createrevision',array('repositoryid'=>$repository->getId()));
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
        $id = $this->params('revisionid');
        $revision = $this->getPageManager()->getRevision($id);
        $revision->setTrashed(true);
        $this->getObjectManager()->persist($revision);
        $this->getObjectManager()->flush();
        $this->redirect()->toRoute('page/article/revisions',array('repositoryid'=>$this->params('repositoryid')));
    }

    public function trashRepositoryAction()
    {
        $pageRepository = $this->getPageRepository();
        $pageRepository->setTrashed(true);
        $this->getObjectManager()->persist($pageRepository);
        $this->getObjectManager()->flush();
        $this->redirect()->toRoute('page');
    }

    
    public function articleAction()
    {
       
        $pageRepository = $this->getPageRepository();
        if ($pageRepository->hasCurrentRevision()) {
            
            $revision = $pageRepository->getCurrentRevision();
            $title = $revision->getTitle();
            $content = $revision->getContent();
            $revisionid = $revision->getId();
        } else
             $revision = NULL;
        
      
      
        $view = new ViewModel(array(
            'revision' => $revision,
            'repositoryid' =>  $pageRepository->getId()
        ));
        
        $view->setTemplate('page/revision.phtml');
        
        return $view;
    }
    
    protected function getPageRepository(){
        $id = $this->params('repositoryid');
        return
        $this->getPageManager()->getPageRepository($id);
    }
}
