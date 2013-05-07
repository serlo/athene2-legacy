<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\LearningObjects\Solution\Controller;

use Entity\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Versioning\Entity\RevisionInterface;

class TextSolutionController extends AbstractController
{

    protected function _getAllowedEntityFactories ()
    {
        return array(
            'Solution\TextSolution'
        );
    }
    
    protected function _getRevisionView(RevisionInterface $revision = NULL){
        if($revision === NULL)
            return NULL;
        
        $revisionView = new ViewModel(array(
            'revision' => $revision
        ));
        
        $revisionView->setTemplate('entity/learning-objects/exercise/text/revision');
        return $revisionView;
    }
    

    public function updateAction ()
    {
        $entity = $this->_getEntity();
        
        $view = new ViewModel(array(
            'entity' => $entity
        ));
        $view->setTemplate('entity/learning-objects/solution/text/form');
        $view->setVariable('form', $entity->getForm());
        
        if ($this->getRequest()->isPost()) {
            $this->_commitRevision($entity);
        }
        
        return $view;
    }
}