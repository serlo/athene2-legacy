<?php
namespace Page\Controller;

use Alias\AliasManagerAwareTrait;
use Alias\AliasManagerInterface;
use Common\Traits\ObjectManagerAwareTrait;
use Instance\Manager\InstanceManagerAwareTrait;
use Instance\Manager\InstanceManagerInterface;
use Page\Form\RepositoryForm;
use Page\Form\RevisionForm;
use Page\Manager\PageManagerAwareTrait;
use Page\Manager\PageManagerInterface;
use User\Manager\UserManagerAwareTrait;
use User\Manager\UserManagerInterface;
use Versioning\RepositoryManagerAwareTrait;
use Versioning\RepositoryManagerInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    use InstanceManagerAwareTrait, RepositoryManagerAwareTrait;
    use PageManagerAwareTrait;
    use UserManagerAwareTrait, AliasManagerAwareTrait;

    /**
     * @var \Page\Form\RepositoryForm
     */
    protected $repositoryForm;

    public function __construct(
        AliasManagerInterface $aliasManager,
        InstanceManagerInterface $instanceManager,
        PageManagerInterface $pageManager,
        RepositoryForm $repositoryForm,
        RepositoryManagerInterface $repositoryManager,
        UserManagerInterface $userManager
    ) {
        $this->aliasManager      = $aliasManager;
        $this->instanceManager   = $instanceManager;
        $this->pageManager       = $pageManager;
        $this->repositoryManager = $repositoryManager;
        $this->userManager       = $userManager;
        $this->repositoryForm    = $repositoryForm;
    }

    public function checkoutAction()
    {
        $id             = $this->params('revision');
        $pageRepository = $this->getPageRepository();
        $this->getRepositoryManager()->getRepository($pageRepository)->checkoutRevision($id);
        $this->getPageManager()->flush();
        return $this->redirect()->toReferer();
    }

    public function createAction()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $form     = $this->repositoryForm;
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
                $this->getPageManager()->flush();
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
        $form = new RevisionForm();
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
                $this->getPageManager()->flush();
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
        $form     = $this->repositoryForm;

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
                $this->getPageManager()->flush();
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
