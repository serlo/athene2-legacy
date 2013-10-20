<?php

/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace LearningResource\Plugin\Repository\Controller;

use Versioning\Exception\RevisionNotFoundException;
use Zend\View\Model\ViewModel;
use Entity\Plugin\Controller\AbstractController;

class RepositoryController extends AbstractController
{
    use\User\Manager\UserManagerAwareTrait;

    public function addRevisionAction()
    {
        $ref = $this->params()->fromQuery('ref', $this->referer()
            ->toUrl('/'));
        
        $repository = $plugin = $this->getPlugin();
        $entity = $this->getEntityService();
        $user = $this->getUserManager()->getUserFromAuthenticator();
        
        $view = new ViewModel(array(
            'entity' => $entity,
            'plugin' => $plugin
        ));
        
        /* @var $form \Zend\Form\Form */
        $form = $plugin->getRevisionForm();
        $form->setAttribute('action', $this->url()
            ->fromRoute('entity/plugin/repository', array(
            'action' => 'add-revision',
            'entity' => $entity->getId()
        )) . '?ref=' . $ref);
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $plugin->commitRevision($form, $user);
                
                $this->getEventManager()->trigger('add-revision', $this, array(
                    'entity' => $entity,
                    'user' => $user,
                    'post' => $this->params()
                        ->fromPost()
                ));
                
                $plugin->getObjectManager()->flush();
                $this->flashMessenger()->addSuccessMessage('Deine Bearbeitung wurde gespeichert. Du erhälst eine Benachrichtigung, sobald deine Bearbeitung geprüft wird.');
                $this->redirect()->toUrl($ref);
                return '';
            }
        }
        
        $view->setTemplate('learning-resource/plugin/repository/update-revision');
        $view->setVariable('form', $form);
        
        return $view;
    }

    public function compareAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $this->getEntityService();
        
        $revision = $this->_getRevision($repository, $this->params('revision'), FALSE);
        $currentRevision = $this->_getRevision($repository);
        
        $view = new ViewModel(array(
            'currentRevision' => $currentRevision,
            'revision' => $revision,
            'entity' => $entity,
            'plugin' => $plugin
        ));
        
        $view->setTemplate('learning-resource/plugin/repository/compare-revision');
        
        return $view;
    }

    public function historyAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $this->getEntityService();
        $currentRevision = $repository->hasCurrentRevision() ? $repository->getCurrentRevision() : NULL;
        $repositoryView = new ViewModel(array(
            'entity' => $entity,
            'revisions' => $repository->getAllRevisions(),
            'trashedRevisions' => $repository->getTrashedRevisions(),
            'currentRevision' => $currentRevision,
            'plugin' => $plugin
        ));
        
        $repositoryView->setTemplate('learning-resource/plugin/repository/history');
        return $repositoryView;
    }

    protected function _getRevision($repository, $id = NULL, $catch = TRUE)
    {
        if ($catch) {
            try {
                if ($id === NULL) {
                    return $repository->getCurrentRevision();
                } else {
                    return $repository->getRevision($id);
                }
            } catch (RevisionNotFoundException $e) {
                return NULL;
            }
        } else {
            if ($id === NULL) {
                return $repository->getCurrentRevision();
            } else {
                return $repository->getRevision($id);
            }
        }
    }

    public function checkoutAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $this->getEntityService();
        $repository->checkout($this->params('revision'));
        
        $user = $this->getUserManager()->getUserFromAuthenticator();
        
        $this->getEventManager()->trigger('checkout', $this, array(
            'entity' => $entity,
            'revision' => $repository->getRevision($this->params('revision')),
            'user' => $user
        ));
        $plugin->getObjectManager()->flush();
        $this->redirect()->toRoute('entity/plugin/repository', array(
            'action' => 'history',
            'entity' => $entity->getId()
        ));
        return '';
    }

    public function purgeRevisionAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $this->getEntityService();
        $repository->removeRevision($this->params('revision'));
        $plugin->getObjectManager()->flush();
        $this->redirect()->toRoute('entity/plugin/repository', array(
            'action' => 'history',
            'entity' => $entity->getId()
        ));
        return '';
    }

    public function trashRevisionAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $this->getEntityService();
        $repository->trashRevision($this->params('revision'));
        $plugin->getObjectManager()->flush();
        $this->redirect()->toRoute('entity/plugin/repository', array(
            'action' => 'history',
            'entity' => $entity->getId()
        ));
        return '';
    }
}