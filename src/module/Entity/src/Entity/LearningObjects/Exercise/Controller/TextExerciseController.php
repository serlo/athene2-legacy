<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\LearningObjects\Exercise\Controller;

use Entity\LearningObjects\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class TextExerciseController extends AbstractController
{
    
    protected function _getAllowedEntityFactories(){
        return array(
            'Exercise\TextExercise'
        );
    }
    
    public function historyAction(){
        $entity = $this->_getEntity();
        $repository = new ViewModel(array('entity' => $entity));
        $revisions = array();
        $repository->setTemplate('entity/learning-objects/core/repository');
        $repository->setVariable('revisions', $entity->getRepositoryComponent()->getAllRevisions());
        return $repository;
    }
    
    public function updateAction(){
        $entity = $this->_getEntity();
        
        $view = new ViewModel(array('entity' => $entity));
        $view->setTemplate('entity/learning-objects/exercise/text/form');
        $view->setVariable('form', $entity->getForm());
        
        if($this->getRequest()->isPost()){
            $this->_commitRevision($entity);
        }
        
        return $view;
    }
}