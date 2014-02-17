<?php

/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Entity\Controller;

use Entity\Entity\EntityInterface;
use Entity\Options\ModuleOptions;
use Instance\Manager\InstanceManagerAwareTrait;
use User\Manager\UserManagerAwareTrait;
use Versioning\Exception\RevisionNotFoundException;
use Versioning\RepositoryManagerAwareTrait;
use Zend\Form\Form;
use Zend\View\Model\ViewModel;

class RepositoryController extends AbstractController
{
    use UserManagerAwareTrait, InstanceManagerAwareTrait;
    use RepositoryManagerAwareTrait;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    public function addRevisionAction()
    {
        $entity = $this->getEntity();
        $this->assertGranted('entity.revision.create', $entity);

        $user = $this->getUserManager()->getUserFromAuthenticator();

        /* @var $form \Zend\Form\Form */
        $form = $this->getForm($entity);

        $view = new ViewModel([
            'entity' => $entity,
            'form'   => $form
        ]);

        if ($this->getRequest()->isPost()) {
            $form->setData(
                $this->getRequest()->getPost()
            );
            if ($form->isValid()) {
                $data     = $form->getData();
                $instance = $this->getInstanceManager()->getInstanceFromRequest();

                $this->getRepositoryManager()->getRepository($entity)->commitRevision($data);
                $this->getEntityManager()->flush();
                $this->flashMessenger()->addSuccessMessage(
                    'Your revision has been saved, it will be available once it get\'s approved'
                );

                $this->redirect()->toUrl(
                    $this->referer()->fromStorage()
                );

                return false;
            }
        } else {
            $this->referer()->store();
        }

        $this->layout('athene2-editor');

        $view->setTemplate('entity/repository/update-revision');

        return $view;
    }

    /**
     * @param EntityInterface $entity
     * @return Form
     */
    protected function getForm(EntityInterface $entity)
    {
        $form = $this->moduleOptions->getType(
            $entity->getType()->getName()
        )->getComponent('repository')->getForm();
        $form = $this->getServiceLocator()->get($form);

        if ($entity->hasCurrentRevision()) {
            $data = [];
            foreach ($entity->getCurrentRevision()->getFields() as $field) {
                $data[$field->getField()] = $field->getValue();
            }
            $form->setData($data);
        }

        return $form;
    }

    public function checkoutAction()
    {
        $entity = $this->getEntity();
        $this->assertGranted('entity.revision.checkout', $entity);

        $user       = $this->getUserManager()->getUserFromAuthenticator();
        $repository = $this->getRepositoryManager()->getRepository($entity);
        $revision   = $repository->findRevision($this->params('revision'));
        $repository->checkoutRevision($revision->getId());

        $this->getEventManager()->trigger(
            'checkout',
            $this,
            [
                'entity'   => $entity,
                'revision' => $revision
            ]
        );

        $this->getEntityManager()->flush();

        $this->redirect()->toRoute(
            'entity/repository/history',
            [
                'entity' => $entity->getId()
            ]
        );

        return false;
    }

    public function compareAction()
    {
        $entity = $this->getEntity();

        $revision        = $this->getRevision($entity, $this->params('revision'));
        $currentRevision = $this->getRevision($entity);

        $view = new ViewModel([
            'currentRevision' => $currentRevision,
            'revision'        => $revision,
            'entity'          => $entity
        ]);

        $view->setTemplate('entity/repository/compare-revision');

        return $view;
    }

    /**
     * @param EntityInterface $entity
     * @param string          $id
     * @return \Versioning\Service\RevisionInterface NULL
     */
    protected function getRevision(EntityInterface $entity, $id = null)
    {
        $repository = $this->getRepositoryManager()->getRepository($entity);
        try {
            if ($id === null) {
                return $entity->getCurrentRevision();
            } else {
                return $repository->findRevision($id);
            }
        } catch (RevisionNotFoundException $e) {
            return null;
        }
    }

    public function historyAction()
    {
        $entity          = $this->getEntity();
        $currentRevision = $entity->hasCurrentRevision() ? $entity->getCurrentRevision() : null;

        $view = new ViewModel([
            'entity'          => $entity,
            'revisions'       => $entity->getRevisions(),
            'currentRevision' => $currentRevision
        ]);

        $view->setTemplate('entity/repository/history');

        return $view;
    }

    public function rejectAction()
    {
        $entity = $this->getEntity();
        $this->assertGranted('entity.revision.trash', $entity);
        $repository = $this->getRepositoryManager()->getRepository($entity);

        $repository->rejectRevision($this->params('revision'), 'some reason');
        $this->getEntityManager()->flush();
        $this->redirect()->toReferer();

        return false;
    }

    /**
     * @param ModuleOptions $moduleOptions
     * @return void
     */
    public function setModuleOptions(ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
    }
}
