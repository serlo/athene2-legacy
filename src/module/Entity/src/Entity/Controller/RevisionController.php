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
namespace Entity\Controller;

use Zend\View\Model\ViewModel;
use Versioning\Entity\RevisionInterface;
use Versioning\Exception\RevisionNotFoundException;
use Core\Structure\GraphDecorator;

abstract class RevisionController extends AbstractController
{

    protected abstract function _getRevisionView (RevisionInterface $revision = NULL);

    public function showRevisionAction ()
    {
        $entity = $this->getEntity();
        $repository = $entity->getRepository();
        $revision = $this->getRevision($this->getParam('revisionId'), false);
        $currentRevision = $this->getRevision();
        
        $view = new ViewModel(array(
            'currentRevision' => $currentRevision,
            'revision' => $revision,
            'entity' => $entity
        ));
        $view->setTemplate('entity/core/compare-revisions');
        
        $revisionView = $this->_getRevisionView($revision);
        $currentRevisionView = $this->_getRevisionView($currentRevision);
        
        $view->addChild($revisionView, 'revisionView');
        if ($currentRevisionView) {
            $view->addChild($currentRevisionView, 'currentRevisionView');
        }
        
        return $view;
    }

    public function historyAction ()
    {
        $entity = $this->getEntity();
        try {
            $currentRevision = $entity->getCurrentRevision();
        } catch (RevisionNotFoundException $e) {
            $currentRevision = NULL;
        }
        $repository = new ViewModel(array(
            'entity' => $entity,
            'currentRevision' => $currentRevision
        ));
        $revisions = array();
        
        $repository->setTemplate('entity/core/repository');
        $repository->setVariable('revisions', $entity->getAllRevisions());
        
        $repository->setVariable('trashedRevisions', $entity->getTrashedRevisions());
        return $repository;
    }

    protected function getRevision ($id = NULL, $catch = TRUE)
    {
        $entity = $this->getEntity();
        $repository = $entity;
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

    public function checkoutAction ()
    {
        $entity = $this->getEntity();
        $entity = $this->getEntity();
        $repository = $entity;
        $repository->checkout($this->getParam('revisionId'));
        $this->redirect()->toRoute($entity->getRoute(), array(
            'action' => 'history',
            'id' => $entity->getId()
        ));
    }

    public function purgeRevisionAction ()
    {
        $entity = $this->getEntity();
        $entity = $this->getEntity();
        $entity->removeRevision($this->getParam('revisionId'));
        $this->redirect()->toRoute($entity->getRoute(), array(
            'action' => 'history',
            'id' => $entity->getId()
        ));
    }

    public function trashRevisionAction ()
    {
        $entity = $this->getEntity();
        $entity->trashRevision($this->getParam('revisionId'));
        $this->redirect()->toRoute($entity->getRoute(), array(
            'action' => 'history',
            'id' => $entity->getId()
        ));
    }

    protected function commitRevision (GraphDecorator $entity)
    {
        if (! $entity->isInstanceOf('\Entity\Components\RepositoryComponentInterface'))
            throw new \InvalidArgumentException();
        
        $form = $entity->getForm();
        $form->setData($this->getRequest()
            ->getPost());
        if ($form->isValid()) {
            $data = $form->getData();
            $entity->commitRevision($data['revision']);
            
            $this->flashMessenger()->addSuccessMessage('Deine Bearbeitung wurde gespeichert. Du erhälst eine Benachrichtigung, sobald deine Bearbeitung geprüft wird.');
        }
        return $entity;
    }
    
    
    /**
     * 
     */
    public function showAction ()
    {
        return $this->show();
    }
    
    public function show($id = NULL){
        $entity = $this->getEntity($id);
        $view = new ViewModel(array('entity' => $entity));
        $view->setTemplate($entity->getTemplate());
        return $view;
    }
}