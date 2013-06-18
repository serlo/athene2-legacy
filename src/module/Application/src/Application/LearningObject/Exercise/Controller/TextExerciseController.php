<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Application\LearningObject\Exercise\Controller;

use Zend\View\Model\ViewModel;
use Versioning\Entity\RevisionInterface;
use Entity\Controller\RevisionController;

class TextExerciseController extends RevisionController
{
    protected function getEntityFactory()
    {
        return 'Application\LearningObject\Exercise\TextExerciseFactory';
    }
    
    protected function getEntityClass()
    {
        return 'Application\LearningObject\Exercise\TextExercise';
    }
    
    public function getRoute(){
        return 'entity/exercise/text';
    }
    
    public function getView(){
        return 'learning-object/exercise/text/show';
    }
    
    protected function _getRevisionView(RevisionInterface $revision = NULL){
        if($revision === NULL)
            return NULL;
        
        $revisionView = new ViewModel(array(
            'revision' => $revision
        ));
        
        $revisionView->setTemplate('learning-object/exercise/text/revision');
        return $revisionView;
    }

    public function updateAction()
    {
        $entity = $this->getEntity();
        
        $view = new ViewModel(array(
            'entity' => $entity
        ));
        $view->setTemplate('learning-object/exercise/text/form');
        $view->setVariable('form', $entity->getForm());
        
        if ($this->getRequest()->isPost()) {
            try {
                $this->commitRevision($entity);
                $entity->getContent();
                $this->redirect()->toRoute($entity->getRoute(), array(
                    'action' => 'show',
                    'id' => $entity->getId()
                ));
            } catch (\Versioning\Exception\RevisionNotFoundException $e) {
                $this->redirect()->toRoute($entity->getRoute(), array(
                    'action' => 'history',
                    'id' => $entity->getId()
                ));
            }
        }
        
        return $view;
    }
    
    public function setTopicAction(){
        $entity = $this->getEntity();
        $topicId = $this->params()->fromQuery('term');
        $entity->setTopic($topicId);

        
        $this->redirect()->toUrl($this->getRequest()
            ->getHeader('Referer')
            ->getUri());
    }
    
    public function getEntity(){
        $entity = parent::getEntity();
        $entity->setController($this);
        return $entity;
    }
}