<?php
namespace Page\Controller;

use Alias\AliasManagerAwareTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Instance\Manager\InstanceManagerAwareTrait;
use Page\Form\RepositoryForm;
use Page\Form\RevisionForm;
use Page\Manager\PageManagerAwareTrait;
use User\Manager\UserManagerAwareTrait;
use Versioning\RepositoryManagerAwareTrait;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    use InstanceManagerAwareTrait, RepositoryManagerAwareTrait;
    use PageManagerAwareTrait, ObjectManagerAwareTrait;
    use UserManagerAwareTrait, AliasManagerAwareTrait;

    public function checkoutAction()
    {
        $id             = $this->params('revision');
        $pageRepository = $this->getPageRepository();
        $this->getRepositoryManager()->getRepository($pageRepository)->checkoutRevision($id);
        $this->getObjectManager()->flush();
        return $this->redirect()->toReferer();
    }

    public function createAction()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $form     = new RepositoryForm($this->getObjectManager());
        $this->assertGranted('page.create', $instance);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data = array_merge($data, ['instance' => $instance->getId()]);
            $form->setData($data);
            if ($form->isValid()) {
                $repository = $this->getPageManager()->createPageRepository($form);
                $data       = $form->getData();
                $params     = ['repository' => $repository, 'slug' => $data['slug']];
                $this->getEventManager()->trigger('page.create', $this, $params);
                $this->getObjectManager()->flush();
                $this->getEventManager()->trigger('page.create.postFlush', $this, $params);
                $this->redirect()->toRoute('page/revision/create', ['page' => $repository->getId()]);
            }
        }

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('page/create');

        return $view;
    }

    public function createRevisionAction()
    {
        $user = $this->getUserManager()->getUserFromAuthenticator();
        $form = new RevisionForm($this->getObjectManager());
        $id   = $this->params('revision');
        $page = $this->getPageRepository();
        $this->assertGranted('page.revision.create', $page);

        if ($id != null) {
            $revision = $this->getPageManager()->getRevision($id);
            $form->get('content')->setValue($revision->getContent());
            $form->get('title')->setValue($revision->getTitle());
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $array           = $form->getData();
                $array['author'] = $user;
                $this->getPageManager()->createRevision($page, $array, $user);
                $this->getObjectManager()->flush();
                return $this->redirect()->toRoute('page/view', ['page' => $page->getId()]);
            }
        }

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('page/revision/create');
        $this->layout('editor/layout');

        return $view;
    }

    public function getPageRepository()
    {
        $id = $this->params('page');
        return $this->getPageManager()->getPageRepository($id);
    }

    public function indexAction()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $pages    = $this->getPageManager()->findAllRepositories($instance);
        $view     = new ViewModel(['pages' => $pages]);
        $view->setTemplate('page/pages');
        return $view;
    }

    public function updateAction()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $page     = $this->getPageRepository();
        $alias    = $this->getAliasManager()->findAliasByObject($page)->getAlias();
        $form     = new RepositoryForm($this->getObjectManager());

        $this->assertGranted('page.update', $page);
        $form->bind($page);
        $form->get('slug')->setValue($alias);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $array  = $form->getData(FormInterface::VALUES_AS_ARRAY);
                $source = $this->url()->fromRoute('page/view', ['page' => $page->getId()]);
                $this->getAliasManager()->createAlias(
                    $source,
                    $array['slug'],
                    $array['slug'] . $page->getId(),
                    $page,
                    $instance
                );
                $this->getPageManager()->editPageRepository($form);
                $this->getObjectManager()->flush();
                $this->redirect()->toUrl($source);
            }
        }

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('page/update');
        return $view;
    }

    public function viewAction()
    {
        $pageRepository = $this->getPageRepository();
        $revision       = $pageRepository->hasCurrentRevision() ? $pageRepository->getCurrentRevision() : null;
        $view           = new ViewModel(['revision' => $revision, 'page' => $pageRepository]);

        $this->assertGranted('page.get', $pageRepository);
        $view->setTemplate('page/revision/view');

        return $view;
    }

    public function viewRevisionAction()
    {
        $id             = $this->params('revision');
        $pageRepository = $this->getPageRepository();
        $revision       = $this->getPageManager()->getRevision($id);
        $view           = new ViewModel(['revision' => $revision, 'page' => $pageRepository]);

        $this->assertGranted('page.get', $pageRepository);
        $view->setTemplate('page/revision/view');

        return $view;
    }

    public function viewRevisionsAction()
    {
        $pageRepository = $this->getPageRepository();
        $revisions      = $pageRepository->getRevisions();
        $view           = new ViewModel(['revisions' => $revisions, 'page' => $pageRepository]);

        $this->assertGranted('page.get', $pageRepository);
        $view->setTemplate('page/revisions');

        return $view;
    }
}
