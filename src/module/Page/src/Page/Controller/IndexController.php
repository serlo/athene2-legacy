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
        $slug = $this->params('slug');
        $id = $this->params('id');
        $language_id = $this->getLanguageManager()
            ->getLanguageFromRequest()
            ->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug, $language_id);
        $pageService->setCurrentRevision($pageService->getRevision($id));
        $this->redirect()->toRoute('page/article', array(
            'slug' => $slug
        ));
        $this->getObjectManager()->persist($pageService->getEntity());
        $this->getObjectManager()->flush();
    }

    public function showRevisionsAction()
    {
        $slug = $this->params('slug');
        $language_id = $this->getLanguageManager()
            ->getLanguageFromRequest()
            ->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug, $language_id);
        $repository = $pageService->getEntity();
        $revisions = $repository->getRevisions();
        $view = new ViewModel(array(
            'revisions' => $revisions,
            'slug' => $slug
        ));
        $view->setTemplate('page/show-revisions.phtml');
        return $view;
    }

    public function showRevisionAction()
    {
        $slug = $this->params('slug');
        $id = $this->params('id');
        
        $language_id = $this->getLanguageManager()
            ->getLanguageFromRequest()
            ->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug, $language_id);
        $revision = $pageService->getRevision($id);
        $view = new ViewModel(array(
            'revision' => $revision,
            'slug' => $slug,
        ));
        
        $view->setTemplate('page/revision.phtml');
        return $view;
    }

    public function editRepositoryAction()
    {
        $form = new RepositoryForm($this->getObjectManager());
        $slug = $this->params('slug');
        $language_id = $this->getLanguageManager()
            ->getLanguageFromRequest()
            ->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug, $language_id);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                $pageService->getEntity()->setSlug($array['slug']);
                $this->getObjectManager()->flush();
                $this->redirect()->toRoute('page/article', array(
                    'slug' => $array['slug']
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
        $slug = $this->params('slug');
        $id = $this->params('id');
        $language = $this->getLanguageManager()
        ->getLanguageFromRequest();
        $language_id = $language->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug, $language_id);
        

        $repository = $pageService->getEntity();
        if ($id != NULL) {
            $form->get('content')->setValue($pageService->getRevision($id)
                ->getContent());
            $form->get('title')->setValue($pageService->getRevision($id)
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
                
            
                
                $this->redirect()->toRoute('page/article',array('slug'=>$slug));
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
               // $url = $this->url()->fromRoute('page/article',array('slug'=>$form->getData()['slug']));
               
                $this->getEventManager()->trigger('page.create', $this, array(
                    'slug' => $array['slug'],
                    'language' => $language,
                    'repository' => $repository->getEntity()
                
                
                ));
                
                
                $this->getObjectManager()->flush();
                $this->redirect()->toRoute('page/article/createrevision',array('slug'=>$form->getData()['slug']));
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
        $slug = $this->params('slug');
        $id = $this->params('revisionid');
        $language_id = $this->getLanguageManager()
        ->getLanguageFromRequest()
        ->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug, $language_id);
        $pageService->trashRevision($id);
        $this->redirect()->toRoute('page/article/revisions',array('slug'=>$slug));
    }

    public function deleteRevisionAction()
    {
        $slug = $this->params('slug');
        $id = $this->params('revisionid');
        $language_id = $this->getLanguageManager()
            ->getLanguageFromRequest()
            ->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug, $language_id);
        $pageService->deleteRevision($id);
        $this->redirect()->toRoute('page/article',array('slug'=>$slug));
    }

    public function deleteRepositoryAction()
    {
        $slug = $this->params('slug');
        $language_id = $this->getLanguageManager()
            ->getLanguageFromRequest()
            ->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug, $language_id);
        $repository = $pageService->getEntity();
        $pageService->getRepositoryManager()->removeRepository($repository);
        $this->getObjectManager()->persist($repository);
        $this->getObjectManager()->remove($repository);
        $this->getObjectManager()->flush();
                $this->redirect()->toRoute('page');
    }

    public function articleAction()
    {
        $slug = $this->params('slug');
        $language_id = $this->getLanguageManager()
            ->getLanguageFromRequest()
            ->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug, $language_id);
        if ($pageService->hasCurrentRevision()) {
            
            $revision = $pageService->getCurrentRevision();
            $title = $revision->getTitle();
            $content = $revision->getContent();
            $revisionid = $revision->getId();
        } else
             $revision = NULL;
        $admin = $pageService->hasPermission($this->getUserManager()->getUserFromAuthenticator());
        
      
        $view = new ViewModel(array(
            'revision' => $revision,
            'slug' => $slug,
        ));
        
        $view->setTemplate('page/revision.phtml');
        
        return $view;
    }
}
