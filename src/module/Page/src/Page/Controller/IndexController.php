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
                $this->redirect()->toRoute('page/view/createrevision', array('repository' => $repository->getId()));
            }
        }

        $view = new ViewModel(['form' => $form]);
        $view->setTemplate('page/create');

        return $view;
    }

    public function createRevisionAction()
    {
        $user           = $this->getUserManager()->getUserFromAuthenticator();
        $form           = new RevisionForm($this->getObjectManager());
        $id             = $this->params('id');
        $pageRepository = $this->getPageRepository();

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
                $this->getPageManager()->createRevision($pageRepository, $array, $user);
                $this->getObjectManager()->flush();
                $this->redirect()->toRoute('page/view', array('repository' => $pageRepository->getId()));
            }
        }

        $view = new ViewModel(array(
            'form'  => $form
        ));

        $view->setTemplate('page/revision/create');

        return $view;
    }

    protected function getPageRepository()
    {
        $id = $this->params('repository');

        return $this->getPageManager()->getPageRepository($id);
    }

    public function editRepositoryAction()
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
                $this->getAliasManager()->updateAlias(
                    $array[$slug],
                    $array[$slug] . $pageRepository->getId(),
                    $pageRepository->getUuidEntity(),
                    $instance
                );
                $this->getPageManager()->editPageRepository($array, $pageRepository);
                $this->getObjectManager()->flush();
                $this->redirect()->toRoute(
                    'page/view',
                    array(
                        'repository' => $pageRepository->getId()
                    )
                );
            }
        }

        $view = new ViewModel(array(
            'form'  => $form,
            'title' => 'Repository bearbeiten'
        ));
        $view->setTemplate('page/form');

        return $view;
    }

    public function indexAction()
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $pages    = $this->getPageManager()->findAllRepositorys($instance);
        $view     = new ViewModel(array('pages' => $pages));
        $view->setTemplate('page/pages');

        return $view;
    }

    public function setCurrentRevisionAction()
    {
        $id             = $this->params('id');
        $pageRepository = $this->getPageRepository();
        $pageRepository->setCurrentRevision($this->getPageManager()->getRevision($id));
        $this->redirect()->toReferer();
        $this->getObjectManager()->persist($pageRepository);
        $this->getObjectManager()->flush();
    }

    public function showRevisionAction()
    {
        $id             = $this->params('revision');
        $pageRepository = $this->getPageRepository();
        $revision       = $this->getPageManager()->getRevision($id);
        $view           = new ViewModel(array(
            'revision'   => $revision,
            'repository' => $pageRepository
        ));

        $view->setTemplate('page/revision/show');

        return $view;
    }

    public function showRevisionsAction()
    {
        $pageRepository = $this->getPageRepository();
        $revisions      = $pageRepository->getRevisions();
        $view           = new ViewModel(array(
            'revisions'  => $revisions,
            'repository' => $pageRepository->getId()
        ));
        $view->setTemplate('page/show-revisions.phtml');

        return $view;
    }

    public function trashRepositoryAction()
    {
        $pageRepository = $this->getPageRepository();
        $pageRepository->setTrashed(true);
        $this->getObjectManager()->persist($pageRepository);
        $this->getObjectManager()->flush();
        $this->redirect()->toRoute('page');
    }

    public function trashRevisionAction()
    {
        $id       = $this->params('revisionid');
        $revision = $this->getPageManager()->getRevision($id);
        $revision->setTrashed(true);
        $this->getObjectManager()->persist($revision);
        $this->getObjectManager()->flush();
        $this->redirect()->toRoute('page/view/revisions', array('repository' => $this->params('repository')));
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
            'revision'   => $revision,
            'repository' => $pageRepository
        ));
        $view->setTemplate('page/revision');

        return $view;
    }
}
