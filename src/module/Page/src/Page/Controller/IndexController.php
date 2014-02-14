<?php
namespace Page\Controller;

use Page\Form\RepositoryForm;
use Page\Form\RevisionForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    use\Instance\Manager\InstanceManagerAwareTrait;
    use \Page\Manager\PageManagerAwareTrait;
    use \Common\Traits\ObjectManagerAwareTrait;
    use\User\Manager\UserManagerAwareTrait;
    use \Alias\AliasManagerAwareTrait;

    public function checkoutAction()
    {
        $id             = $this->params('revision');
        $pageRepository = $this->getPageRepository();
        $pageRepository->setCurrentRevision($this->getPageManager()->getRevision($id));
        $this->getObjectManager()->persist($pageRepository);
        $this->getObjectManager()->flush();

        return $this->redirect()->toReferer();
    }

    protected function getPageRepository()
    {
        $id = $this->params('page');

        return $this->getPageManager()->getPageRepository($id);
    }

    public function createAction()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $form     = new RepositoryForm($this->getObjectManager());

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data       = $form->getData();
                $repository = $this->getPageManager()->createPageRepository($form->getData(), $instance);

                $this->getEventManager()->trigger(
                    'page.create',
                    $this,
                    [
                        'instance'   => $instance,
                        'repository' => $repository,
                        'slug'       => $data['slug']
                    ]
                );

                $this->getObjectManager()->flush();
                $this->redirect()->toRoute('page/revision/create', array('page' => $repository->getId()));
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

        if ($id != null) {
            $form->get('content')->setValue(
                $this->getPageManager()->getRevision($id)->getContent()
            );
            $form->get('title')->setValue(
                $this->getPageManager()->getRevision($id)->getTitle()
            );
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $array           = $form->getData();
                $array['author'] = $user;
                $this->getPageManager()->createRevision($page, $array, $user);
                $this->getObjectManager()->flush();
                $this->redirect()->toRoute('page/view', array('page' => $page->getId()));
            }
        }

        $view = new ViewModel(array(
            'form' => $form
        ));

        $view->setTemplate('page/revision/create');
        $this->layout('editor/layout');

        return $view;
    }

    public function indexAction()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $pages    = $this->getPageManager()->findAllRepositories($instance);
        $view     = new ViewModel(array('pages' => $pages));
        $view->setTemplate('page/pages');

        return $view;
    }

    public function updateAction()
    {
        $form = new RepositoryForm($this->getObjectManager());

        $instance       = $this->getInstanceManager()->getInstanceFromRequest();
        $pageRepository = $this->getPageRepository();
        $form->get('slug')->setValue(
            $this->getAliasManager()->findAliasByObject($pageRepository->getUuidEntity())->getAlias()
        );
        $roles = array();
        foreach ($pageRepository->getRoles() as $role) {
            $roles[] = $role->getId();
        }
        $form->get('roles')->setValue($roles);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $array = $form->getData();
                $source = $this->url()->fromRoute('page/view', array('page' => $pageRepository->getId()));
                $this->getAliasManager()->createAlias(
                    $source,
                    $array['slug'],
                    $array['slug'] . $pageRepository->getId(),
                    $pageRepository->getUuidEntity(),
                    $instance
                );
                $this->getPageManager()->editPageRepository($array, $pageRepository);
                $this->getObjectManager()->flush();
                $this->redirect()->toUrl($source);
            }
        }

        $view = new ViewModel(array(
            'form' => $form,
        ));

        $view->setTemplate('page/create');

        return $view;
    }

    public function viewAction()
    {
        $pageRepository = $this->getPageRepository();
        if ($pageRepository->hasCurrentRevision()) {
            $revision = $pageRepository->getCurrentRevision();
        } else {
            $revision = null;
        }

        $view = new ViewModel(array(
            'revision' => $revision,
            'page'     => $pageRepository
        ));
        $view->setTemplate('page/revision/view');

        return $view;
    }

    public function viewRevisionAction()
    {
        $id             = $this->params('revision');
        $pageRepository = $this->getPageRepository();
        $revision       = $this->getPageManager()->getRevision($id);
        $view           = new ViewModel(array(
            'revision' => $revision,
            'page'     => $pageRepository
        ));

        $view->setTemplate('page/revision/view');

        return $view;
    }

    public function viewRevisionsAction()
    {
        $pageRepository = $this->getPageRepository();
        $revisions      = $pageRepository->getRevisions();
        $view           = new ViewModel(array(
            'revisions' => $revisions,
            'page'      => $pageRepository
        ));
        $view->setTemplate('page/revisions');

        return $view;
    }
}
