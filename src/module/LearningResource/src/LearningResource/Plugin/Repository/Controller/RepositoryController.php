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

    public function addRevisionAction()
    {
        $ref = $this->params()->fromQuery('ref', $this->referer()->toUrl('/'));
        
        $repository = $plugin = $this->getPlugin();
        $entity = $plugin->getEntityService();
        
        $view = new ViewModel(array(
            'entity' => $entity
        ));
        
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
                $plugin->commitRevision($form);
                $entity->getObjectManager()->flush();
                $this->flashMessenger()->addSuccessMessage('Deine Bearbeitung wurde gespeichert. Du erhälst eine Benachrichtigung, sobald deine Bearbeitung geprüft wird.');
                $this->redirect()->toUrl($ref);
            }
        }
        
        $view->setTemplate('learning-resource/plugin/repository/update-revision');
        $view->setVariable('form', $form);
        
        return $view;
    }

    public function compareAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $plugin->getEntityService();
        $revision = $this->_getRevision($repository, $this->params('revision'), FALSE);
        $currentRevision = $this->_getRevision($repository);
        
        $view = new ViewModel(array(
            'currentRevision' => $currentRevision,
            'revision' => $revision,
            'entity' => $entity
        ));
        
        $view->setTemplate('learning-resource/plugin/repository/compare-revision');
        
        $revisionView = $this->getRevision($this->params('revision'));
        $currentRevisionView = $this->getRevision();
        
        $view->addChild($revisionView, 'revisionView');
        
        if ($currentRevisionView) {
            $view->addChild($currentRevisionView, 'currentRevisionView');
        }
        
        return $view;
    }

    public function historyAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $plugin->getEntityService();
        try {
            $currentRevision = $entity->getCurrentRevision();
        } catch (RevisionNotFoundException $e) {
            $currentRevision = NULL;
        }
        $repositoryView = new ViewModel(array(
            'entity' => $entity,
            'revisions' => $repository->getAllRevisions(),
            'trashedRevisions' => $repository->getTrashedRevisions(),
            'currentRevision' => $currentRevision
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
        $entity = $plugin->getEntityService();
        $repository->checkout($this->params('revision'));
        $entity->getObjectManager()->flush();
        $this->redirect()->toRoute('entity/plugin/repository', array(
            'action' => 'history',
            'entity' => $entity->getId()
        ));
    }

    public function purgeRevisionAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $plugin->getEntityService();
        $repository->removeRevision($this->params('revision'));
        $entity->getObjectManager()->flush();
        $this->redirect()->toRoute('entity/plugin/repository', array(
            'action' => 'history',
            'entity' => $entity->getId()
        ));
    }

    public function trashRevisionAction()
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $plugin->getEntityService();
        $repository->trashRevision($this->params('revision'));
        $entity->getObjectManager()->flush();
        $this->redirect()->toRoute('entity/plugin/repository', array(
            'action' => 'history',
            'entity' => $entity->getId()
        ));
    }

    public function getHeadAction()
    {
        return $this->getRevision();
    }

    public function revisionAction()
    {
        return $this->getRevision($this->params('revision'));
    }

    public function getRevision($revisionId = NULL)
    {
        $repository = $plugin = $this->getPlugin();
        $entity = $plugin->getEntityService();
        $revision = $this->_getRevision($repository, $revisionId);
        $view = new ViewModel(array(
            'entity' => $entity,
            'repository' => $repository,
            'revision' => $revision
        ));
        $view->setTemplate('learning-resource/plugin/repository/revision');
        return $view;
    }
    
    /*
     * protected function commitRevision ($entity) { $form = $entity->getForm(); $form->setData($this->getRequest() ->getPost()); if ($form->isValid()) { $data = $form->getData(); $entity->commitRevision($data['repository']['revision']); $entity->getObjectManager()->flush(); $this->flashMessenger()->addSuccessMessage('Deine Bearbeitung wurde gespeichert. Du erhälst eine Benachrichtigung, sobald deine Bearbeitung geprüft wird.'); } return $entity; }
     */
}