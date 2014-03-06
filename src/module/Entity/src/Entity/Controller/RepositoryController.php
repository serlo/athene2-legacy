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
        if (!$entity) {
            return false;
        }
        $this->assertGranted('entity.revision.create', $entity);

        /* @var $form \Zend\Form\Form */
        $user = $this->getUserManager()->getUserFromAuthenticator();
        $form = $this->getForm($entity);
        $view = new ViewModel(['entity' => $entity, 'form' => $form]);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $data     = $form->getData();
                $instance = $this->getInstanceManager()->getInstanceFromRequest();

                $this->getRepositoryManager()->getRepository($entity)->commitRevision($data);
                $this->getEntityManager()->flush();
                $this->flashMessenger()->addSuccessMessage(
                    'Your revision has been saved, it will be available once it get\'s approved'
                );

                return $this->redirect()->toUrl($this->referer()->fromStorage());
            }
        } else {
            $this->referer()->store();
        }

        $this->layout('athene2-editor');
        $view->setTemplate('entity/repository/update-revision');

        return $view;
    }

    public function checkoutAction()
    {
        $entity = $this->getEntity();
        if (!$entity) {
            return false;
        }
        $this->assertGranted('entity.revision.checkout', $entity);

        $repository = $this->getRepositoryManager()->getRepository($entity);
        $revision   = $repository->findRevision($this->params('revision'));
        $repository->checkoutRevision($revision->getId(), 'Approved');
        $this->getEntityManager()->flush();
        return $this->redirect()->toRoute('entity/repository/history', ['entity' => $entity->getId()]);
    }

    public function compareAction()
    {
        $entity = $this->getEntity();
        if (!$entity) {
            return false;
        }

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

    public function historyAction()
    {
        $entity          = $this->getEntity();
        $currentRevision = $entity->hasCurrentRevision() ? $entity->getCurrentRevision() : null;
        $this->assertGranted('entity.repository.history', $entity);
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
        if (!$entity) {
            return false;
        }
        $this->assertGranted('entity.revision.trash', $entity);
        $repository = $this->getRepositoryManager()->getRepository($entity);

        $repository->rejectRevision($this->params('revision'), 'Rejected');
        $this->getEntityManager()->flush();
        return $this->redirect()->toReferer();
    }

    /**
     * @param ModuleOptions $moduleOptions
     * @return void
     */
    public function setModuleOptions(ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
    }

    /**
     * @param EntityInterface $entity
     * @return Form
     */
    protected function getForm(EntityInterface $entity)
    {
        $type = $entity->getType()->getName();
        $form = $this->moduleOptions->getType($type)->getComponent('repository')->getForm();
        $form = $this->getServiceLocator()->get($form);

        if ($entity->hasCurrentRevision()) {
            $data = [];
            foreach ($entity->getCurrentRevision()->getFields() as $field) {
                $data[$field->getName()] = $field->getValue();
            }
            $form->setData($data);
        }

        return $form;
    }

    /**
     * @param EntityInterface $entity
     * @param string          $id
     * @return \Versioning\Entity\RevisionInterface NULL
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
}
