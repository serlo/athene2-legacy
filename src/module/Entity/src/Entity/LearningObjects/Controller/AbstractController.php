<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\LearningObjects\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Entity\EntityManagerInterface;
use Zend\View\Model\ViewModel;
use Entity\Factory\EntityBuilderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

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
    
    protected abstract function _getAllowedEntityFactories();

    public function indexAction (){
        return $this->receiveAction();
    }
    
    protected function _getEntity(){
        $id = $this->getParam('id');
        $entity = $this->getEntityManager()->get($id); 
        $controllerName =$this->params('controller');
        $actionName = $this->params('action');
        
        if(!in_array($entity->getFactoryClassName(), $this->_getAllowedEntityFactories())){
            //$this->redirect()->toRoute(get_class($entity),array('action' => $actionName, 'id' => $entity->getId()));
            throw new \Exception('This controller can\'t handle the requested entity.');
        }
        
        return $entity;
    }
    
    protected function _commitRevision(EntityBuilderInterface $entity){
        $form = $entity->getForm();
        $form->setData($this->getRequest()->getPost());
        if($form->isValid()){
            $data = $form->getData();
            $entity->getRepositoryComponent()->commitRevision($data['revision']);
        
            $this->flashMessenger()->addSuccessMessage('Deine Bearbeitung wurde gespeichert. Du erhälst eine Benachrichtigung, sobald deine Bearbeitung geprüft wird.');
            $this->redirect()->toRoute(get_class($entity),array('action' => 'show', 'id' => $entity->getId()));
        }
    }

    public function showAction(){
        $entity = $this->_getEntity();
        return $entity->toViewModel();
    }
}