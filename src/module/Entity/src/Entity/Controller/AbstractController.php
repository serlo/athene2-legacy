<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Entity\EntityManagerInterface;
use Zend\View\Model\ViewModel;
use Entity\Factory\EntityBuilderInterface;
use Versioning\Entity\RevisionInterface;
use Versioning\Exception\RevisionNotFoundException;

abstract class AbstractController extends AbstractActionController
{

    /**
     *
     * @var EntityManagerInterface
     */
    protected $_entityManager;

    /**
     *
     * @return EntityManagerInterface $_entityManager
     */
    public function getEntityManager ()
    {
        return $this->_entityManager;
    }

    /**
     *
     * @param EntityManagerInterface $_entityManager            
     * @return $this
     */
    public function setEntityManager (EntityManagerInterface $_entityManager)
    {
        $this->_entityManager = $_entityManager;
        return $this;
    }

    protected abstract function _getAllowedEntityFactories ();

    public function indexAction ()
    {
        return $this->receiveAction();
    }

    protected function getEntity ()
    {
        $id = $this->getParam('id');
        $entity = $this->getEntityManager()->get($id);
        $controllerName = $this->params('controller');
        $actionName = $this->params('action');
        
        if (! in_array(get_class($entity), $this->_getAllowedEntityFactories())) {
            // $this->redirect()->toRoute(get_class($entity),array('action' => $actionName, 'id' => $entity->getId()));
            throw new \Exception('This controller can\'t handle the requested entity.');
        }
        
        return $entity;
    }

    protected function commitRevision (EntityBuilderInterface $entity)
    {
        $form = $entity->getForm();
        $form->setData($this->getRequest()
            ->getPost());
        if ($form->isValid()) {
            $data = $form->getData();
            $entity->getRepository()->commitRevision($data['revision']);
            
            $this->flashMessenger()->addSuccessMessage('Deine Bearbeitung wurde gespeichert. Du erhälst eine Benachrichtigung, sobald deine Bearbeitung geprüft wird.');
            $this->redirect()->toRoute(get_class($entity), array(
                'action' => 'show',
                'id' => $entity->getId()
            ));
        }
    }

    protected abstract function _getRevisionView (RevisionInterface $revision = NULL);

    public function showRevisionAction ()
    {
        $entity = $this->getEntity();
        $repository = $entity->getRepository();
        $revision = $this->_getRevision($this->getParam('revisionId'), false);
        $currentRevision = $this->_getRevision();
        
        $view = new ViewModel(array(
            'currentRevision' => $currentRevision,
            'revision' => $revision,
            'entity' => $entity
        ));
        $view->setTemplate('entity/learning-objects/core/compare-revisions');
        
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
        
        $repository->setTemplate('entity/learning-objects/core/repository');
        $repository->setVariable('revisions', $entity->getAllRevisions());
        
        $repository->setVariable('trashedRevisions', $entity->getTrashedRevisions());
        return $repository;
    }

    protected function _getRevision ($id = NULL, $catch = TRUE)
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
        $this->redirect()->toRoute(get_class($entity), array(
            'action' => 'history',
            'id' => $entity->getId()
        ));
    }

    public function showAction ()
    {
        $entity = $this->getEntity();
        $entity = $this->getEntity();
        return $entity->toViewModel();
    }

    public function purgeRevisionAction ()
    {
        $entity = $this->getEntity();
        $entity = $this->getEntity();
        $entity->removeRevision($this->getParam('revisionId'));
        $this->redirect()->toRoute(get_class($entity), array(
            'action' => 'history',
            'id' => $entity->getId()
        ));
    }

    public function trashRevisionAction ()
    {
        $entity = $this->getEntity();
        $entity->trashRevision($this->getParam('revisionId'));
        $this->redirect()->toRoute(get_class($entity), array(
            'action' => 'history',
            'id' => $entity->getId()
        ));
    }
}