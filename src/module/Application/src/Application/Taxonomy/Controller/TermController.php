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
namespace Application\Taxonomy\Controller;

use Zend\View\Model\ViewModel;
use Taxonomy\Controller\AbstractController;

class TermController extends AbstractController
{

    public function updateAction ()
    {
        $id = $this->params()->fromPost('id') ? $this->params()->fromPost('id') : $this->params()->fromQuery('id');
        $term = $this->getTerm($id);
        
        $view = new ViewModel(array(
            'id' => $id,
            'ref' => $this->getRequest()->getHeader('Referer')->getUri()
        ));
        
        $form = $term->getForm();
        $view->setVariable('form', $form);
        $view->setTemplate('taxonomy/default/update');
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if($form->isValid()){
                $term->update($form->getData());
                $this->flashMessenger()->addSuccessMessage('Bearbeitung erfoglreich gespeichert!');
                $this->redirect()->toUrl($this->params()->fromQuery('ref'));
            }
        }
        
        return $view;
    }

    public function createAction ()
    {
        
    }

    public function deleteAction ()
    {}

    protected function getTerm ($id = 0)
    {
        if($id){
            return $this->getSharedTaxonomyManager()->getTerm($id);
        }
        return $this->getSharedTaxonomyManager()->getTerm($this->getParam('id'));
    }
}