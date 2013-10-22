<?php
namespace Page\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Page\Form\RepositoryForm;
use Page\Form\RevisionForm;
use User\Service\UserService;
use Page\Exception\PermissionException;
use Page\Exception\PageNotFoundException;
use Zend\Form\Form;

class IndexController extends AbstractActionController
{
    use \Language\Manager\LanguageManagerAwareTrait;
    use\Page\Manager\PageManagerAwareTrait;
    use\Common\Traits\ObjectManagerAwareTrait;
    use \User\Manager\UserManagerAwareTrait;
    
    public function setCurrentRevisionAction(){
        
        $slug = $this->params('slug');
        $id = $this->params('id');
        $language_id = $this->getLanguageManager()->getLanguageFromRequest()->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug,$language_id);
        $pageService->setCurrentRevision($pageService->getRevision($id));
        $this->redirect()->toUrl('/page/'.$slug.'/');
        $this->getObjectManager()->persist($pageService->getEntity());
        $this->getObjectManager()->flush();  
    
    }
    
    public function showRevisionsAction(){
        $slug = $this->params('slug');
        $language_id = $this->getLanguageManager()->getLanguageFromRequest()->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug,$language_id);
        $repository = $pageService->getEntity();
        $revisions = $repository->getRevisions();
        return new ViewModel(array(
            'revisions' => $revisions,
            'slug' => $slug
        ));
    
    
    }

    public function showRevisionAction(){
        $slug = $this->params('slug');
        $id = $this->params('id');
        
        $language_id = $this->getLanguageManager()->getLanguageFromRequest()->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug,$language_id);
        $revision = $pageService->getRevision($id);
        return new ViewModel(array(
            'revision' => $revision,
            'slug' => $slug
        ));
        
        
    }
    
    
    public function editRepositoryAction(){
        $form = new RepositoryForm($this->getObjectManager());
        $slug = $this->params('slug');
        $language_id = $this->getLanguageManager()->getLanguageFromRequest()->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug,$language_id);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                $pageService->getEntity()->setSlug($array['slug']);
                $this->getObjectManager()->flush();
                $this->redirect()->toUrl('/page/'.$array['slug'].'/');
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        return $view;
        
    }

    public function createRevisionAction(){
        $us = $this->getUserManager()->getUserFromAuthenticator();
        $form = new RevisionForm($this->getObjectManager());
        $slug = $this->params('slug');
        $id = $this->params('id'); 
        $language_id = $this->getLanguageManager()->getLanguageFromRequest()->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug,$language_id);
        
       // if (!$pageService->hasPermission($us)) throw new PermissionException();
        
        $repository = $pageService->getEntity();
        if ($id!=NULL){
        $form->get('content')->setValue($pageService->getRevision($id)->getContent());
        $form->get('title')->setValue($pageService->getRevision($id)->getTitle());
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                $page = $this->getPageManager()->createRevision($repository, $form->getData());
                $this->getObjectManager()->flush();
                $this->redirect()->toUrl('/page/'.$repository->getSlug().'/');
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        return $view;
    }



    public function createRepositoryAction()
    {
        
        $language = $this->getLanguageManager()->getLanguageFromRequest()->getEntity();
        $form = new RepositoryForm($this->getObjectManager()); 
        $us = $this->getUserManager()->getUserFromAuthenticator();
        if ($us==null) throw new PermissionException();
        if (!$us->hasRole("sysadmin")&&!$us->hasRole("admin")&&!$us->hasRole("moderator")) throw new PermissionException();
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                $repository = $this->getPageManager()->createPageRepository($form->getData(),$language);
                $this->getObjectManager()->flush();
                $this->redirect()->toUrl('/page/'.$array['slug'].'/');
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        return $view;
    }

   
    public function deleteRevisionAction()
    {
        
        $slug = $this->params('slug');
        $id = $this->params('revisionid');
        $language_id = $this->getLanguageManager()->getLanguageFromRequest()->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug,$language_id);
        $repository = $pageService->getRepositoryManager()->getRepository($pageService->getEntity());
        $revision = $pageService->getRevision($id); 
        $repository->removeRevision($id);
        $this->getObjectManager()->remove($revision);
        $this->getObjectManager()->flush();
        $this->redirect()->toUrl('/page/'.$slug.'/');    
    }
    
    public function deleteRepositoryAction()
    {
        $slug = $this->params('slug');
        $language_id = $this->getLanguageManager()->getLanguageFromRequest()->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug,$language_id);
        $repository = $pageService->getEntity();
        $pageService->getRepositoryManager()->removeRepository($repository);
        $this->getObjectManager()->persist($repository);
        $this->getObjectManager()->remove($repository);
        $this->getObjectManager()->flush();
        $this->redirect()->toUrl('/page/');
    
    
    }

    public function articleAction()
    {
        $slug = $this->params('slug');
        $language_id = $this->getLanguageManager()->getLanguageFromRequest()->getId();
        $pageService = $this->getPageManager()->findPageRepositoryBySlug($slug,$language_id);
        if ($pageService->hasCurrentRevision()) {
            
            $revision = $pageService->getCurrentRevision();
            $array = $pageService->getContentAndTitleFromRevision($revision);
            $title = $array['title'];
            $content = $array['content'];
            $revisionid = $revision->getId();
            
            
        } else $title=$content=$revisionid='';
        
        return new ViewModel(array(
            'content' => $content,
            'title' => $title,
            'slug' => $slug,
            'revisionid' => $revisionid
        ));
    }
}
