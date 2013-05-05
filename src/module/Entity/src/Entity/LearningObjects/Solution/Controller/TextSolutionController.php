<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\LearningObjects\Solution\Controller;

use Entity\LearningObjects\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class TextSolutionController extends AbstractController
{

    protected function _getAllowedEntityFactories ()
    {
        return array(
            'Solution\TextSolution'
        );
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